<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Widgets\UserOverview;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;

class UserResource extends Resource
{
    /**
     * Модель, к которой привязан ресурс
     */
    protected static ?string $model = User::class;

    /**
     * Иконка, группа, лейблы
     */
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Администрирование';
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $modelLabel      = 'Пользователи';
    
    /**
     * Какое поле модели использовать как заголовок записи
     */
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Кто может получить доступ к ресурсу
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    /**
     * Заголовок для глобального поиска Filament
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    /**
     * Какие поля искать в глобальном поиске
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    /**
     * Боковое меню с помощью пакета filament-page-with-sidebar.
     */
    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            // Если нет $record — ставим заглушку '—'
            ->setTitle($record?->name ?? '—')

            // created_at теперь Carbon, значит format() не упадёт
            ->setDescription($record && $record->created_at
                ? $record->created_at->format('d.m.Y H:i')
                : ''
            )
            ->setNavigationItems([
                PageNavigationItem::make(__('base.list_user'))
                    ->translateLabel()
                    ->url(static::getUrl('index'))
                    ->icon('heroicon-o-user-group')
                    ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index')),

                PageNavigationItem::make(__('base.view_user'))
                    ->translateLabel()
                    ->url(static::getUrl('view', ['record' => $record->id]))
                    ->icon('heroicon-o-user')
                    ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.view')),

                PageNavigationItem::make(__('base.edit_user'))
                    ->translateLabel()
                    ->url(static::getUrl('edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil-square')
                    ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.edit')),

                PageNavigationItem::make(__('base.change_password'))
                    ->translateLabel()
                    ->url(static::getUrl('password.change', ['record' => $record->id]))
                    ->icon('heroicon-o-key')
                    ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.password.change')),
            ]);
    }

    /**
     * Описание формы (create/edit)
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Имя')
                            ->placeholder('Введите имя')
                            ->required()
                            ->maxLength(191),

                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->placeholder('Введите e-mail')
                            ->email()
                            ->required()
                            ->maxLength(191),

                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                            ->maxLength(191),

                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->maxLength(191),

                        Forms\Components\Select::make('inviter')
                            ->label('Аффилиат')
                            ->relationship('affiliate', 'name')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live(),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Подтверждение E-mail'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('affiliate_revenue_share')
                            ->label('Revenue Share (%)')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('affiliate_revenue_share_fake')
                            ->label('Revenue Share Fake (%)')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('affiliate_cpa')
                            ->label('CPA')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('affiliate_baseline')
                            ->label('Baseline')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('banned')
                            ->label('Заблокирован')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_demo_agent')
                            ->label('Influencer')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('status')
                            ->label('Статус')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Описание таблицы (List)
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),

                // Пример столбца 'created_at' (дата)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),

                // Другие столбцы...
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Пример фильтра по дате
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Создан от'),
                        DatePicker::make('created_until')->label('Создан до'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] =
                                'Создан от ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] =
                                'Создан до ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('details')
                    ->label('Детали')
                    ->icon('heroicon-o-chart-bar')
                    ->color('gray')
                    ->action(function (User $user) {
                        return redirect()->to(
                            route('filament.admin.resources.users.detail', ['record' => $user])
                        );
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    /**
     * Если у вас есть какие-то отношения (Relations) — опишите тут
     */
    public static function getRelations(): array
    {
        return [];
    }

    /**
     * Виджеты, если нужны
     */
    public static function getWidgets(): array
    {
        return [
            UserOverview::class,
        ];
    }

    /**
     * Роуты для страниц ресурса
     */
    public static function getPages(): array
    {
        return [
            'index'           => Pages\ListUsers::route('/'),
            'create'          => Pages\CreateUser::route('/create'),
            'edit'            => Pages\EditUser::route('/{record}/edit'),
            'view'            => Pages\ViewUser::route('/{record}/view'),
            'detail'          => Pages\DetailUser::route('/{record}/detail'),
            'password.change' => Pages\ChangePasswordUser::route('/{record}/password/change'),
        ];
    }
}