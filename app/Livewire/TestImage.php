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

class TestImage extends Component
{

    use WithFileUploads;

    public $image;


    public function mount()
    {
        $post = TestImageModel::first();
        $gallery = $post->getMedia('gallery')
            ->where('variant_name', 'thumbnail')
            ->first()
            ->getUrl();

        echo $gallery;

        $src = $post->getMedia('feature')
            ->findVariant('thumbnail')
            ->getUrl();

        // dd($gallery);

    }


    public function save(Request $request)
    {
        // $manipulation = ImageManipulation::make(function (Image $image, Media $originalMedia) {
        //     $image->resize(100, 100);
        // })->outputPngFormat();


        $media = MediaUploader::fromSource($this->image)
            ->toDestination('public', 'test-image/' . date('Ymd'))
            ->useHashForFilename('sha1')
            // ->applyImageManipulation($manipulation)
            ->onDuplicateUpdate()

            


            ->upload();

        $post = TestImageModel::first();
        $post->attachMedia($media, ['gallery']);

        $variantMedia = ImageManipulator::createImageVariant($media, 'thumbnail');

        $post->attachMedia($variantMedia, ['gallery']);

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
