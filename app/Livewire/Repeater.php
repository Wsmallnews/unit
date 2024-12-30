<?php

namespace App\Livewire;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Locked;
use Illuminate\Contracts\View\View;
use Wsmallnews\Category\Enums;
use Wsmallnews\Category\Category as CategoryManager;
use Wsmallnews\Category\Models\CategoryType;
use Wsmallnews\Support\Concerns\RepeaterTree;

class Repeater extends Page
{

    use InteractsWithForms;
    use RepeaterTree;


    #[Locked]
    public Model | int | string | null $record = null;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    // public function mount(int | string | null $record): void
    public function mount(): void
    {
        $this->record = CategoryType::with('categories')->find(1);

        $this->form->fill($this->record?->toArray());
    }



    // public function repeaterField($fields = [], $relation_name = 'children', $name = 'children'): Components\Repeater
    // {
    //     return Components\Repeater::make($name ?: $relation_name)
    //         ->relationship($relation_name)
    //         ->reorderable(true)
    //         ->schema($fields);
    // }


    public function fields(): array
    {
        return [
            Components\Group::make([
                Components\TextInput::make('name')
                    ->hiddenLabel()
                    ->placeholder('请输入分类名称')
                    ->required(),

                Components\Group::make([
                    Components\FileUpload::make('icon')
                        ->hiddenLabel()
                        ->placeholder('上传图标')
                        ->image()
                        ->directory(CategoryManager::getImageDirectory())
                        ->openable()
                        ->imageResizeMode('cover')
                        ->imageResizeUpscale(false)
                        ->imageCropAspectRatio('1:1')
                        ->imageResizeTargetHeight('100')
                        ->imageResizeTargetWidth('100')
                        ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                        ->imagePreviewHeight('64')
                        ->removeUploadedFileButtonPosition('center bottom')
                        ->uploadProgressIndicatorPosition('right bottom')
                        // ->extraAttributes([
                        //     'class' => 'w-16 h-16 overflow-hidden border border-gray-300 rounded-md',
                        // ])
                        ->uploadingMessage('分类图标上传中...')
                        ->columnSpan(1),
                    Components\FileUpload::make('image')
                        ->hiddenLabel()
                        ->placeholder('上传图片')
                        ->image()
                        ->directory(CategoryManager::getImageDirectory())
                        ->openable()
                        ->imageResizeMode('cover')
                        ->imageResizeUpscale(false)
                        ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                        ->imagePreviewHeight('64')
                        ->removeUploadedFileButtonPosition('center bottom')
                        ->uploadProgressIndicatorPosition('right bottom')
                        ->uploadingMessage('分类图片上传中...')
                        ->columnSpan(1),
                    ])
                    ->columns(2),

                Components\TextInput::make('description')
                    ->hiddenLabel()
                    ->placeholder('请输入分类描述')
                    ->required(),

                Components\Radio::make('status')
                    ->hiddenLabel()
                    ->options(Enums\CategoryStatus::class)
                    ->default(Enums\CategoryStatus::Normal->value)
                    ->inline()
                    ->required()
            ])
            ->columns(4)
            ->columnSpanFull(),
        ];
    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->repeaterField($this->getFieldsTree(3), relation_name: 'categories'),


                // Components\Repeater::make('categories')
                //     ->relationship('categories')
                //     ->reorderable(true)
                //     ->schema([
                //         Components\Group::make([
                //             Components\TextInput::make('name')
                //                 ->hiddenLabel()
                //                 ->placeholder('请输入分类名称')
                //                 ->required(),

                //             Components\Group::make([
                //                 Components\FileUpload::make('icon')
                //                     ->hiddenLabel()
                //                     ->placeholder('上传图标')
                //                     ->image()
                //                     ->directory(CategoryManager::getImageDirectory())
                //                     ->openable()
                //                     ->imageResizeMode('cover')
                //                     ->imageResizeUpscale(false)
                //                     ->imageCropAspectRatio('1:1')
                //                     ->imageResizeTargetHeight('100')
                //                     ->imageResizeTargetWidth('100')
                //                     ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                //                     ->imagePreviewHeight('64')
                //                     ->removeUploadedFileButtonPosition('center bottom')
                //                     ->uploadProgressIndicatorPosition('right bottom')
                //                     // ->extraAttributes([
                //                     //     'class' => 'w-16 h-16 overflow-hidden border border-gray-300 rounded-md',
                //                     // ])
                //                     ->uploadingMessage('分类图标上传中...')
                //                     ->columnSpan(1),
                //                 Components\FileUpload::make('image')
                //                     ->hiddenLabel()
                //                     ->placeholder('上传图片')
                //                     ->image()
                //                     ->directory(CategoryManager::getImageDirectory())
                //                     ->openable()
                //                     ->imageResizeMode('cover')
                //                     ->imageResizeUpscale(false)
                //                     ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                //                     ->imagePreviewHeight('64')
                //                     ->removeUploadedFileButtonPosition('center bottom')
                //                     ->uploadProgressIndicatorPosition('right bottom')
                //                     ->uploadingMessage('分类图片上传中...')
                //                     ->columnSpan(1),
                //             ])
                //                 ->columns(2),

                //             Components\TextInput::make('description')
                //                 ->hiddenLabel()
                //                 ->placeholder('请输入分类描述')
                //                 ->required(),

                //             Components\Radio::make('status')
                //                 ->hiddenLabel()
                //                 ->options(Enums\CategoryStatus::class)
                //                 ->default(Enums\CategoryStatus::Normal->value)
                //                 ->inline()
                //                 ->required(),
                //         ])
                //         ->columns(4)
                //         ->columnSpanFull(),
                //         Components\Group::make([
                //             Components\Repeater::make('children')
                //                 ->relationship('children')
                //                 ->hiddenLabel()
                //                 ->reorderable(true)
                //                 ->schema([
                //                     Components\Group::make([
                //                         Components\TextInput::make('name')
                //                             ->hiddenLabel()
                //                             ->placeholder('请输入分类名称')
                //                             ->required(),

                //                         Components\Group::make([
                //                             Components\FileUpload::make('icon')
                //                                 ->hiddenLabel()
                //                                 ->placeholder('上传图标')
                //                                 ->image()
                //                                 ->directory(CategoryManager::getImageDirectory())
                //                                 ->openable()
                //                                 ->imageResizeMode('cover')
                //                                 ->imageResizeUpscale(false)
                //                                 ->imageCropAspectRatio('1:1')
                //                                 ->imageResizeTargetHeight('100')
                //                                 ->imageResizeTargetWidth('100')
                //                                 ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                //                                 ->imagePreviewHeight('64')
                //                                 ->removeUploadedFileButtonPosition('center bottom')
                //                                 ->uploadProgressIndicatorPosition('right bottom')
                //                                 // ->extraAttributes([
                //                                 //     'class' => 'w-16 h-16 overflow-hidden border border-gray-300 rounded-md',
                //                                 // ])
                //                                 ->uploadingMessage('分类图标上传中...')
                //                                 ->columnSpan(1),
                //                             Components\FileUpload::make('image')
                //                                 ->hiddenLabel()
                //                                 ->placeholder('上传图片')
                //                                 ->image()
                //                                 ->directory(CategoryManager::getImageDirectory())
                //                                 ->openable()
                //                                 ->imageResizeMode('cover')
                //                                 ->imageResizeUpscale(false)
                //                                 ->itemPanelAspectRatio(1)   // 正方形 1:1(但是没效果)
                //                                 ->imagePreviewHeight('64')
                //                                 ->removeUploadedFileButtonPosition('center bottom')
                //                                 ->uploadProgressIndicatorPosition('right bottom')
                //                                 ->uploadingMessage('分类图片上传中...')
                //                                 ->columnSpan(1),
                //                         ])
                //                         ->columns(2),

                //                         Components\TextInput::make('description')
                //                             ->hiddenLabel()
                //                             ->placeholder('请输入分类描述')
                //                             ->required(),

                //                         Components\Radio::make('status')
                //                             ->hiddenLabel()
                //                             ->options(Enums\CategoryStatus::class)
                //                             ->default(Enums\CategoryStatus::Normal->value)
                //                             ->inline()
                //                             ->required(),
                //                     ])
                //                     ->columns(4)
                //                     ->columnSpanFull(),
                //                 ]),
                //         ])
                //         ->columnSpanFull(),
                //     ])
                //     ->columnSpan('full'),
            ])
            ->statePath('data')
            ->model($this->getRecord() ?: new CategoryType());
    }



    public function getRecord(): ?CategoryType
    {
        return $this->record;
    }



    public function render(): View
    {
        return view('livewire.repeater');
    }
}
