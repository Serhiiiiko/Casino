<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Contracts\Support\Htmlable;

class DefaultSetting extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.default-setting';

    /**
     * @dev @victormsalatiel
     * @param Model $record
     * @return bool
     */
    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return __('По умолчанию');
    }

    public Setting $record;
    public ?array $data = [];

    /**
     * @dev victormsalatiel - Мой Instagram
     * @return void
     */
    public function mount(): void
    {
        $setting = Setting::first();
        $this->record = $setting;
        $this->form->fill($setting->toArray());
    }

    /**
     * @return void
     */
    public function save()
    {
        try {
            if (env('APP_DEMO')) {
                Notification::make()
                    ->title('Внимание')
                    ->body('Вы не можете выполнить это изменение в демо-версии')
                    ->danger()
                    ->send();
                return;
            }

            $setting = Setting::find($this->record->id);

            $favicon             = $this->data['software_favicon'];
            $logoWhite           = $this->data['software_logo_white'];
            $logoBlack           = $this->data['software_logo_black'];
            $softwareBackground  = $this->data['software_background'];

            // Обработка background
            if (is_array($softwareBackground) || is_object($softwareBackground)) {
                if (!empty($softwareBackground)) {
                    $this->data['software_background'] = $this->uploadFile($softwareBackground);

                    if (is_array($this->data['software_background'])) {
                        unset($this->data['software_background']);
                    }
                }
            }

            // Обработка favicon
            if (is_array($favicon) || is_object($favicon)) {
                if (!empty($favicon)) {
                    $this->data['software_favicon'] = $this->uploadFile($favicon);

                    if (is_array($this->data['software_favicon'])) {
                        unset($this->data['software_favicon']);
                    }
                }
            }

            // Обработка светлого логотипа
            if (is_array($logoWhite) || is_object($logoWhite)) {
                if (!empty($logoWhite)) {
                    $this->data['software_logo_white'] = $this->uploadFile($logoWhite);

                    if (is_array($this->data['software_logo_white'])) {
                        unset($this->data['software_logo_white']);
                    }
                }
            }

            // Обработка тёмного логотипа
            if (is_array($logoBlack) || is_object($logoBlack)) {
                if (!empty($logoBlack)) {
                    $this->data['software_logo_black'] = $this->uploadFile($logoBlack);

                    if (is_array($this->data['software_logo_black'])) {
                        unset($this->data['software_logo_black']);
                    }
                }
            }

            // Запись данных в .env
            $envs = DotenvEditor::load(base_path('.env'));
            $envs->setKeys([
                'APP_NAME' => $this->data['software_name'],
            ]);
            $envs->save();

            // Обновление настроек
            if ($setting->update($this->data)) {
                Cache::put('setting', $setting);

                Notification::make()
                    ->title('Данные изменены')
                    ->body('Данные успешно изменены!')
                    ->success()
                    ->send();

                redirect(route('filament.admin.resources.settings.index'));
            }
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @dev victormsalatiel - Мой Instagram
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Визуальные настройки')
                    ->description('Форма для настройки внешнего вида платформы')
                    ->schema([
                        Group::make()->schema([
                            TextInput::make('software_name')
                                ->label('Название')
                                ->placeholder('Введите название сайта')
                                ->required()
                                ->maxLength(191),
                            TextInput::make('software_description')
                                ->label('Описание')
                                ->placeholder('Введите описание сайта')
                                ->maxLength(191),
                        ])->columns(2),

                        Group::make()->schema([
                            FileUpload::make('software_favicon')
                                ->label('Favicon')
                                ->placeholder('Загрузите favicon')
                                ->image(),
                            Group::make()->schema([
                                FileUpload::make('software_logo_white')
                                    ->label('Светлый логотип')
                                    ->placeholder('Загрузите светлый логотип')
                                    ->image()
                                    ->columnSpanFull(),
                                FileUpload::make('software_logo_black')
                                    ->label('Тёмный логотип')
                                    ->placeholder('Загрузите тёмный логотип')
                                    ->image()
                                    ->columnSpanFull(),
                                // Если нужно вернуть загрузку фона, раскомментируйте блок ниже:
                                /*
                                FileUpload::make('software_background')
                                    ->label('Фоновое изображение')
                                    ->placeholder('Загрузите фоновое изображение')
                                    ->image()
                                    ->columnSpanFull(),
                                */
                            ]),
                        ])->columns(2),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * @dev victormsalatiel - Мой Instagram
     * @param $array
     * @return mixed|void
     */
    private function uploadFile($array)
    {
        if ((!empty($array) && is_array($array)) || (!empty($array) && is_object($array))) {
            foreach ($array as $k => $temporaryFile) {
                if ($temporaryFile instanceof TemporaryUploadedFile) {
                    $path = \Helper::upload($temporaryFile);
                    if ($path) {
                        return $path['path'];
                    }
                } else {
                    return $temporaryFile;
                }
            }
        }
    }
}
