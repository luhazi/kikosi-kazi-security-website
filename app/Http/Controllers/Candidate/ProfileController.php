<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()
            ->candidateProfile()
            ->with(['education', 'experience', 'documents'])
            ->firstOrNew(['user_id' => Auth::id()]);

        // Always sync stored completeness with actual data
        if ($profile->exists) {
            $pct = self::calculateCompleteness($profile);
            if ($profile->completeness_pct !== $pct) {
                $profile->completeness_pct = $pct;
                $profile->save();
            }
        }

        $educations  = $profile->education  ?? collect();
        $experiences = $profile->experience ?? collect();

        return view('candidate.profile.index', compact('profile', 'educations', 'experiences'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone'           => ['required', 'string', 'max:20'],
            'alternate_phone' => ['nullable', 'string', 'max:20'],
            'gender'          => ['required', 'in:male,female,other'],
            'date_of_birth'   => ['required', 'date', 'before:today'],
            'national_id'     => ['nullable', 'string', 'max:50'],
            'nationality'     => ['required', 'string', 'max:100'],
            'address'         => ['required', 'string', 'max:255'],
            'city'            => ['required', 'string', 'max:100'],
            'region'          => ['required', 'string', 'max:100'],
            'bio'             => ['nullable', 'string', 'max:2000'],
        ]);

        $profile = Auth::user()->candidateProfile()->firstOrNew(['user_id' => Auth::id()]);

        if ($request->hasFile('profile_photo')) {
            $request->validate(['profile_photo' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048']]);
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('photos', 'public');
        }

        $profile->fill($validated);
        $profile->user_id = Auth::id();
        $profile->completeness_pct = $this->calculateCompleteness($profile);
        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Calculate profile completeness percentage (0–100).
     * Requires personal fields + at least 1 education + 1 experience entry.
     */
    public static function calculateCompleteness(CandidateProfile $profile): int
    {
        $fields = [
            'profile_photo',
            'phone', 'gender', 'date_of_birth',
            'nationality', 'address', 'city', 'region',
        ];

        $filled = 0;
        // +3 for: education, experience, and an uploaded academic certificate
        $total  = count($fields) + 3;

        foreach ($fields as $field) {
            if (!empty($profile->{$field})) {
                $filled++;
            }
        }

        // Education: at least one record
        $eduCount = $profile->education()->count();
        if ($eduCount > 0) $filled++;

        // Experience: at least one record
        $expCount = $profile->experience()->count();
        if ($expCount > 0) $filled++;

        // Academic certificate: at least one uploaded certificate/diploma document
        // (the candidate uploads whichever certificate matches their education level)
        if ($profile->documents()->where('file_type', 'certificate')->exists()) {
            $filled++;
        }

        return (int) round(($filled / $total) * 100);
    }

    public function storeEducation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'institution'    => ['required', 'string', 'max:255'],
            'qualification'  => ['required', 'string', 'max:255'],
            'field_of_study' => ['required', 'string', 'max:255'],
            'year_completed' => ['required', 'integer', 'min:1950', 'max:' . date('Y')],
            'grade'          => ['nullable', 'string', 'max:50'],
        ]);

        $profile = Auth::user()->candidateProfile()->firstOrCreate(['user_id' => Auth::id()]);
        $profile->education()->create($validated);

        // Recalculate completeness
        $profile->completeness_pct = self::calculateCompleteness($profile);
        $profile->save();

        return redirect()->back()->with('success', 'Education record added.');
    }

    public function destroyEducation(int $id): RedirectResponse
    {
        $edu = Auth::user()->candidateProfile->education()->findOrFail($id);
        $edu->delete();

        $profile = Auth::user()->candidateProfile;
        $profile->completeness_pct = self::calculateCompleteness($profile);
        $profile->save();

        return redirect()->back()->with('success', 'Education record removed.');
    }

    public function storeExperience(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employer'         => ['required', 'string', 'max:255'],
            'job_title'        => ['required', 'string', 'max:255'],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['nullable', 'date', 'after_or_equal:start_date'],
            'responsibilities' => ['nullable', 'string', 'max:2000'],
        ]);

        $profile = Auth::user()->candidateProfile()->firstOrCreate(['user_id' => Auth::id()]);
        $profile->experience()->create($validated);

        // Recalculate completeness
        $profile->completeness_pct = self::calculateCompleteness($profile);
        $profile->save();

        return redirect()->back()->with('success', 'Experience record added.');
    }

    public function destroyExperience(int $id): RedirectResponse
    {
        $exp = Auth::user()->candidateProfile->experience()->findOrFail($id);
        $exp->delete();

        $profile = Auth::user()->candidateProfile;
        $profile->completeness_pct = self::calculateCompleteness($profile);
        $profile->save();

        return redirect()->back()->with('success', 'Experience record removed.');
    }
}
