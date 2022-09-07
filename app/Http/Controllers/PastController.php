<?php

namespace App\Http\Controllers;

use App\Events\PastDeleted;
use App\Events\PastUpdated;
use App\Models\Past;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PastController extends Controller
{
    /**
     * Display a Welcome page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $image = asset('logo.png');
        return Inertia::render('Welcome', compact('image'));
    }

    /**
     * Display a Qr scanner page.
     *
     * @return Response
     */
    public function scan(): Response
    {
        $qr_scan_bg = asset('img/qr_scan_bg.svg');
        return Inertia::render('QrScanner', compact('qr_scan_bg'));
    }

    /**
     * find resource using the code.
     *
     * @param $code
     * @return RedirectResponse
     */
    public function code($code): RedirectResponse
    {
        $past = Past::where('code', $code)->first();
        return redirect()->route('pasts.show', $past);
    }

    /**
     * Store the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (Past::count() > 50) {
            return redirect()->route('sorry');
        }

        $request->validate([
            'code' => 'nullable|string'
        ]);

        $code = $request->get('code');

        $past = Past::updateOrCreate([
            'code' => $code
        ], [
            'code' => $code
        ]);

        event(new PastUpdated($past));
        return redirect()->route('pasts.show', $past);
    }

    /**
     * Display the specified resource.
     *
     * @param Past $past
     * @return Response
     */
    public function show(Past $past): Response
    {
        $url = route('code', $past->code);
        return Inertia::render('Past', compact('past', 'url'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Past $past
     * @return Response
     */
    public function update(Request $request, Past $past): Response
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $past->content = $request->get('content');
        $past->save();

        event(new PastUpdated($past));
        $url = route('pasts.show', $past);
        return Inertia::render('Past', compact('past', 'url'));
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param Request $request
     * @param Past $past
     * @return RedirectResponse
     */
    public function destroy(Past $past, Request $request): RedirectResponse
    {
        $mediaFiles = $past->getMedia('files');
        foreach ($mediaFiles as /* @var $mediaFile Media */ $mediaFile) {
            $past->delete($mediaFile->id);
        }

        event(new PastDeleted($past));
        $past->delete();

        if ($request->has('new')) {
            return redirect()->route('welcome');
        }

        return redirect()->route('thanks');
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function thanks(): Response
    {
        $image = asset('logo.png');
        return Inertia::render('ThankYou', compact('image'));
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function sorry(): Response
    {
        $image = asset('logo.png');
        return Inertia::render('Sorry', compact('image'));
    }

    /**
     * Upload a file to the specified resource in storage.
     *
     * @param Request $request
     * @param Past $past
     * @return \Illuminate\Http\RedirectResponse
     */
    public function files(Request $request, Past $past): \Illuminate\Http\RedirectResponse
    {
        $mediaCount = $past->getMedia('files')->count();
        $filesRule = $mediaCount >= 4 ? 'prohibited' : 'max:' . (4 - $mediaCount);

        $request->validate([
            'files' => $filesRule,
            'files.*' => ['required', 'max:10000', 'file']
        ], [
            'files.*.max' => 'max size is 10Mb',
            'files.max' => 'max number of files is 4',
            'files.prohibited' => 'max number of files is 4'
        ]);

        try {
            foreach ($request->file('files') as $file) {
                $past->addMedia($file)->toMediaCollection('files', 's3');
            }
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            \Log::error($e);
        }

        event(new PastUpdated($past));
        return redirect()->route('pasts.show', $past);
    }

    /**
     * Delete a file from the specified resource in storage.
     *
     * @param Past $past
     * @param $id
     * @return RedirectResponse
     * @throws MediaCannotBeDeleted
     */
    public function deleteFile(Past $past, $id): \Illuminate\Http\RedirectResponse
    {
        $past->deleteMedia($id);

        event(new PastUpdated($past));
        return redirect()->route('pasts.show', $past);
    }
}
