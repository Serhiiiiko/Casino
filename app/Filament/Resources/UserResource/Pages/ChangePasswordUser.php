<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;

class ChangePasswordUser extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    public User $record;
    public ?array $data = [];

    protected static string $resource = UserResource::class;
    protected static string $view = 'filament.resources.user-resource.pages.change-password-user';

    protected static ?string $title = 'Изменить пароль';

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * @return void
     */
    public function save()
    {
        try {
            $user = User::find($this->record->id);

            $user->update(['password' => $this->data['password']]);

            Notification::make()
                ->title('Пароль изменён')
                ->body('Пароль был успешно изменён!')
                ->success()
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @return array|\Filament\Forms\Components\Component[]
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('Измените свой пароль')
                ->description('Форма для изменения нового пароля')
                ->schema([
                    TextInput::make('password')
                        ->label('Пароль')
                        ->placeholder('Введите пароль')
                        ->password()
                        ->required()
                        ->maxLength(191),
                    TextInput::make('confirm_password')
                        ->label('Подтвердите пароль')
                        ->placeholder('Подтвердите свой пароль')
                        ->password()
                        ->confirmed()
                        ->maxLength(191),
                ])
                ->columns(2)
                ->statePath('data'),
        ];
    }
}
