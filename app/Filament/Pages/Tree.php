<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Wsmallnews\FilamentNestedset\Pages\TreePage;

class Tree extends TreePage
{

    protected static ?string $model = \App\Models\Tree::class;

    protected static ?string $modelLabel = '🌳树';

    protected static ?string $title = '测试树';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';



    protected function schema(array $arguments): array
    {
        return [
            Forms\Components\TextInput::make('name')->label('名称')
                ->placeholder('请输入名称')
                ->required(),
            Forms\Components\TextInput::make('remark')->label('描述')
                ->placeholder('请输入描述')
                ->required(),
        ];
    }
}
