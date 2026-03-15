<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'voter' => $request->user('voter') ? [
                    'id' => $request->user('voter')->id,
                    'name' => $request->user('voter')->name,
                    'email' => $request->user('voter')->email,
                    'course' => $request->user('voter')->course,
                    'google_id' => $request->user('voter')->google_id,
                    'avatar' => $request->user('voter')->avatar ?? null,
                    'campus_email' => $request->user('voter')->campus_email ?? null,
                ] : null,
                'admin' => $request->user('admin') ? [
                    'id' => $request->user('admin')->id,
                    'name' => $request->user('admin')->name,
                    'email' => $request->user('admin')->email,
                ] : null,
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'info' => fn() => $request->session()->get('info'),
            ],
            'urls' => $this->sharedUrls($request),
        ];
    }

    /**
     * Build shared URL list, only including routes that are registered.
     */
    private function sharedUrls(Request $request): array
    {
        $safeRoute = function (string $name, mixed ...$params) {
            return Route::has($name) ? route($name, ...$params) : '#';
        };

        return [
            'voter' => [
                'dashboard' => $safeRoute('voter.dashboard'),
                'vote' => $safeRoute('voter.vote'),
                'votes' => $safeRoute('voter.votes'),
                'results' => $safeRoute('voter.results'),
                'profile' => $safeRoute('voter.profile'),
                'profile_edit' => $safeRoute('voter.profile.edit'),
                'profile_update' => $safeRoute('voter.profile.update'),
                'logout' => $safeRoute('voter.logout'),
                'register' => $safeRoute('voter.register.form'),
                'register_submit' => $safeRoute('voter.register'),
                'login' => $safeRoute('voter.login'),
                'google_redirect' => $safeRoute('voter.google.redirect'),
                'voting_confirmation' => $safeRoute('voter.voting.confirmation'),
                'submit_vote' => $safeRoute('voter.submit'),
            ],
            'admin' => [
                'dashboard' => $safeRoute('admin.dashboard'),
                'elections_index' => $safeRoute('admin.elections.index'),
                'elections_create' => $safeRoute('admin.elections.create'),
                'elections_store' => $safeRoute('admin.elections.store'),
                'voters_index' => $safeRoute('admin.voters.index'),
                'results_index' => $safeRoute('admin.results.index'),
                'announcements_index' => $safeRoute('admin.announcements.index'),
                'announcements_store' => $safeRoute('admin.announcements.store'),
                'login' => $safeRoute('admin.login'),
                'logout' => $safeRoute('admin.logout'),
            ],
        ];
    }
}
