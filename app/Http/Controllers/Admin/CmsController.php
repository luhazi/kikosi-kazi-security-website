<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\GalleryPhoto;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function index(): View
    {
        $services      = Service::orderBy('category')->orderBy('sort_order')->get();
        $testimonials  = Testimonial::orderBy('sort_order')->get();
        $teamMembers   = TeamMember::orderBy('sort_order')->get();
        $galleryPhotos = GalleryPhoto::orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderByDesc('created_at')
            ->get();
        $clients = Client::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.cms.index', compact('services', 'testimonials', 'teamMembers', 'galleryPhotos', 'clients'));
    }

    // ─── Services ───────────────────────────────────────────────────────────

    public function storeService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:services,slug'],
            'category'    => ['required', 'in:security,hr,insurance,cleaning'],
            'description' => ['required', 'string'],
            'icon'        => ['nullable', 'string', 'max:100'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->back()->with('success', 'Service added.');
    }

    public function updateService(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'unique:services,slug,' . $service->id],
            'category'    => ['required', 'in:security,hr,insurance,cleaning'],
            'description' => ['required', 'string'],
            'icon'        => ['nullable', 'string', 'max:100'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->back()->with('success', 'Service updated.');
    }

    public function destroyService(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->back()->with('success', 'Service deleted.');
    }

    // ─── Testimonials ────────────────────────────────────────────────────────

    public function storeTestimonial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'company'     => ['nullable', 'string', 'max:255'],
            'quote'       => ['required', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        Testimonial::create($validated);

        return redirect()->back()->with('success', 'Testimonial added.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'company'     => ['nullable', 'string', 'max:255'],
            'quote'       => ['required', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $testimonial->update($validated);

        return redirect()->back()->with('success', 'Testimonial updated.');
    }

    public function destroyTestimonial(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->back()->with('success', 'Testimonial deleted.');
    }

    // ─── Team Members ────────────────────────────────────────────────────────────

    public function storeTeamMember(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'role'        => ['required', 'string', 'max:255'],
            'department'  => ['nullable', 'string', 'max:255'],
            'bio'         => ['nullable', 'string'],
            'email'       => ['nullable', 'email', 'max:255'],
            'linkedin'    => ['nullable', 'url', 'max:255'],
            'is_ceo'      => ['boolean'],
            'ceo_message' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($validated);

        return redirect()->back()->with('success', 'Team member added.');
    }

    public function updateTeamMember(Request $request, TeamMember $teamMember): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'role'        => ['required', 'string', 'max:255'],
            'department'  => ['nullable', 'string', 'max:255'],
            'bio'         => ['nullable', 'string'],
            'email'       => ['nullable', 'email', 'max:255'],
            'linkedin'    => ['nullable', 'url', 'max:255'],
            'is_ceo'      => ['boolean'],
            'ceo_message' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['boolean'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            if ($teamMember->photo_path) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('team', 'public');
        }

        $teamMember->update($validated);

        return redirect()->back()->with('success', 'Team member updated.');
    }

    public function destroyTeamMember(TeamMember $teamMember): RedirectResponse
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }
        $teamMember->delete();

        return redirect()->back()->with('success', 'Team member removed.');
    }

    // ─── Photo Gallery ──────────────────────────────────────────────────────

    private function galleryRules(bool $imageRequired = false): array
    {
        return [
            'title'        => ['nullable', 'string', 'max:255'],
            'caption'      => ['nullable', 'string', 'max:1000'],
            'category'     => ['nullable', 'string', 'in:' . implode(',', array_keys(GalleryPhoto::CATEGORIES))],
            'event_date'   => ['nullable', 'date'],
            'sort_order'   => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
            'image'        => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function storeGalleryPhoto(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->galleryRules(true));

        $validated['image_path']   = $request->file('image')->store('gallery', 'public');
        $validated['is_published'] = $request->boolean('is_published', true);
        unset($validated['image']);

        GalleryPhoto::create($validated);

        return redirect()->back()->with('success', 'Photo added to the gallery.');
    }

    public function updateGalleryPhoto(Request $request, GalleryPhoto $galleryPhoto): RedirectResponse
    {
        $validated = $request->validate($this->galleryRules());

        if ($request->hasFile('image')) {
            if ($galleryPhoto->image_path) {
                Storage::disk('public')->delete($galleryPhoto->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }
        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['image']);

        $galleryPhoto->update($validated);

        return redirect()->back()->with('success', 'Photo updated.');
    }

    public function destroyGalleryPhoto(GalleryPhoto $galleryPhoto): RedirectResponse
    {
        if ($galleryPhoto->image_path) {
            Storage::disk('public')->delete($galleryPhoto->image_path);
        }
        $galleryPhoto->delete();

        return redirect()->back()->with('success', 'Photo removed from the gallery.');
    }

    // ─── Clients ────────────────────────────────────────────────────────────

    private function clientRules(bool $logoRequired = false): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'website'    => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
            'logo'       => [$logoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ];
    }

    public function storeClient(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->clientRules(true));

        $validated['logo_path'] = $request->file('logo')->store('clients', 'public');
        $validated['is_active'] = $request->boolean('is_active', true);
        unset($validated['logo']);

        Client::create($validated);

        return redirect()->back()->with('success', 'Client added.');
    }

    public function updateClient(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate($this->clientRules());

        if ($request->hasFile('logo')) {
            if ($client->logo_path) {
                Storage::disk('public')->delete($client->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('clients', 'public');
        }
        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['logo']);

        $client->update($validated);

        return redirect()->back()->with('success', 'Client updated.');
    }

    public function destroyClient(Client $client): RedirectResponse
    {
        if ($client->logo_path) {
            Storage::disk('public')->delete($client->logo_path);
        }
        $client->delete();

        return redirect()->back()->with('success', 'Client removed.');
    }
}
