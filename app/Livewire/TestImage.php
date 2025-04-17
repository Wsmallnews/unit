<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\Request;
use App\Models\TestImage as TestImageModel;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\ImageManipulation;
use Intervention\Image\Image;
use Plank\Mediable\Media;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TestImage extends Component
{

    use WithFileUploads;

    public $image;


    public function mount()
    {
        // echo  sha1('test-image-' . Str::ulid());
        // echo "<br>";
        // echo Str::ulid();
        // echo "<br>";
        // echo Str::uuid();
        // exit;


        // $post = TestImageModel::first();
        // $gallery = $post->getMedia('gallery')
        //     ->where('variant_name', 'thumbnail')
        //     ->first()
        //     ->getUrl();

        // echo $gallery;

        // $src = $post->getMedia('feature')
        //     ->findVariant('thumbnail')
        //     ->getUrl();

        // dd($gallery);

    }

    private function getUploadedFileNameForStorageScopeInfo(TemporaryUploadedFile $file, array $scopeInfo): string
    {
        return sha1($scopeInfo['scope_type'] . '-' . $scopeInfo['scope_id'] . '-' . hash_file('sha1', $file->getRealPath()));
    }

    public function save(Request $request)
    {
        // $manipulation = ImageManipulation::make(function (Image $image, Media $originalMedia) {
        //     $image->resize(100, 100);
        // })->outputPngFormat();

        $scopeInfo = ['scope_type' => 'shop', 'scope_id' => 5];
        $filename = $this->getUploadedFileNameForStorageScopeInfo($this->image, $scopeInfo);

        $media = MediaUploader::fromSource($this->image)
            ->toDestination('public', 'test-image/' . date('Ymd'))
            ->useFilename($filename)
            // ->useHashForFilename('sha1')
            // ->applyImageManipulation($manipulation)
            ->onDuplicateUpdate()

            


            ->upload();

        // $post = TestImageModel::first();
        // $post->attachMedia($media, ['gallery']);

        // $variantMedia = ImageManipulator::createImageVariant($media, 'thumbnail');

        // $post->attachMedia($variantMedia, ['gallery']);

        dd($media);
    }

    public function ddd()
    {
        $media = MediaUploader::fromSource($this->image)
            ->toDestination($component->getDiskName(), $component->getDirectory())
            ->useHashForFilename('sha1')
            ->onDuplicateUpdate()

            ->setMaximumSize($component->getMaxSize())
            ->setAllowedMimeTypes($component->getAcceptedFileTypes())

            // only allow files of specific extensions
            ->setAllowedExtensions($component->getExtensions())

            ->setAllowedAggregateTypes($component->getAggregateTypes())
            // model class to use
            // ->setModelClass(MediaSubclass::class)

            ->withOptions($component->getCustomHeaders())

            ->upload();
    }


    public function render()
    {
        




        return view('livewire.test-image');
    }
}
