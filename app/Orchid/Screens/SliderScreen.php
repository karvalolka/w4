<?php

namespace App\Orchid\Screens;


use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;


class SliderScreen extends Screen
{

    public function query(): array
    {
        return [
            'sliders' => Slider::latest()->get(),
        ];
    }


    public function name(): ?string
    {
        return "List of components for the slider";
    }

    public function create(Request $request)
    {

        $validatedData = $request->validate([
            'slider.title' => 'required|string|max:255',
            'slider.description' => 'nullable|string|max:500',
            'slider.button_text' => 'nullable|string|max:50',
            'slider.button_link' => 'nullable|url',
            'slider.order' => 'required|integer|unique:sliders,order',
            'slider.color_text' => 'nullable|string',
            'slider.img' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slider = new Slider($validatedData);
        $slider->title = $request->input('slider.title');
        $slider->description = $request->input('slider.description');
        $slider->button_text = $request->input('slider.button_text');
        $slider->button_link = $request->input('slider.button_link');
        $slider->order = $request->input('slider.order');
        $slider->color_text = $request->input('slider.color_text');

        if ($request->hasFile('slider.img')) {
            $slider->img = $request->file('slider.img')->store('sliders', 'public');
        }

        $slider->save();
        return redirect()->route('platform.slider')->with('success', 'Slider created successfully!');
    }

    public function delete(Slider $slider)
    {
        try {
            $slider->delete();
            return redirect()->route('platform.slider')->with('success', 'Slider deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('platform.slider')->with('error', 'Error deleting slider.');
        }
    }

    public function description(): ?string
    {
        return "SliderScreen";
    }


    public function commandBar(): array
    {
        return [
            ModalToggle::make('Add composites')
                ->modal('sliderModal')
                ->method('create')
                ->icon('plus'),
        ];
    }


    public function layout(): array
    {
        return [
            Layout::modal('sliderModal', Layout::rows([
                Input::make('slider.title')
                    ->title('Title')
                    ->placeholder('Enter slider title')
                    ->help('The title of the slider.')
                    ->error('slider.title'),

                Input::make('slider.description')
                    ->title('Description')
                    ->placeholder('Enter slider description')
                    ->help('The description of the slider.')
                    ->error('slider.description'),

                Input::make('slider.button_text')
                    ->title('Button Text')
                    ->placeholder('Enter button text')
                    ->help('Text for the button on the slider.')
                    ->error('slider.button_text'),

                Input::make('slider.button_link')
                    ->title('Button Link')
                    ->placeholder('Enter button link')
                    ->help('Link for the button on the slider.')
                    ->error('slider.button_link'),

                Input::make('slider.order')
                    ->title('Order')
                    ->type('number')
                    ->placeholder('Enter order number')
                    ->help('Order number of the slider.')
                    ->min(1)
                    ->step(1)
                    ->error('slider.order'),

                Input::make('slider.color_text')
                    ->title('Text Color')
                    ->type('color')
                    ->help('Выберите цвет для текста слайдера.')
                    ->error('slider.color_text'),

                Input::make('slider.img')
                    ->title('Image')
                    ->type('file')
                    ->help('Upload an image for the slider.')
                    ->error('slider.img'),
            ]))
                ->title('Create Component')
                ->applyButton('Add Slider'),

            Layout::table('sliders', [
                TD::make('title', 'Title'),
                TD::make('description', 'Description'),
                TD::make('order', 'Order'),
                TD::make('button_text', 'Button Text'),
                TD::make('button_link', 'Button Link'),
                TD::make('color_text', 'Color Text')
                    ->render(function (Slider $slider) {
                        return '<span style="display:inline-block; width: 20px; height: 20px; background-color: ' . htmlspecialchars($slider->color_text) . '; border: 1px solid #000;"></span>';
                    }),
                TD::make('img', 'Image')
                    ->render(function (Slider $slider) {
                        $imgSrc = asset('storage/' . $slider->img);
                        return '<a href="' . $imgSrc . '" target="_blank">
                    <img src="' . $imgSrc . '" alt="Slider Image" style="width: 50px; height: auto;">
                </a>';
                    }),



                TD::make('Actions')
                    ->alignRight()
                    ->render(function (Slider $slider) {
                        return Button::make('Delete Slider')
                            ->confirm('After deleting, the slider will be gone forever.')
                            ->method('delete', ['slider' => $slider->id]);
                    }),
            ]),
        ];
    }
}

