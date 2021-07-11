<?php namespace Kpolicar\BackendTrafficCop;

use App;
use Backend;
use Backend\Widgets\Form as BackendForm;
use Carbon\Carbon;
use Kpolicar\BackendMenuPinnedPages\Models\PinnedPage;
use Kpolicar\BackendTrafficCop\Exceptions\ModelHasChangedException;
use October\Rain\Database\Model;
use October\Rain\Exception\ApplicationException;
use RainLab\Blog\Models\Post;
use System\Classes\PluginBase;
use Backend\Classes\Controller as BackendController;
use Backend\Models\User as BackendUser;
use BackendAuth;

/**
 * BackendTrafficCop Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'kpolicar.backendtrafficcop::lang.plugin.name',
            'description' => 'kpolicar.backendtrafficcop::lang.plugin.description',
            'author'      => 'Klemen Janez Poličar',
            'icon'        => 'icon-random'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        App::error(function (ModelHasChangedException $e) {
            return e(trans('kpolicar.backendtrafficcop::lang.popup.message'));
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        if (!App::runningInBackend())
            return;


        BackendForm::extend(function($widget) {
            $widget->addJs('/plugins/kpolicar/backendtrafficcop/assets/js/traffic-cop.js', ['defer' => true]);
        });

        \Event::listen('backend.form.extendFields', function (BackendForm $form) {
            if (!$form->model->exists)
                return;
            $form->addFields([
                '_retrieved_at' => [
                    'type' => 'text',
                    'readOnly' => true,
                    'containerAttributes' => ['js-retrieved-at' => ''],
                    'cssClass' => 'hidden',
                ]
            ]);
            $form->getField('_retrieved_at')->value = now();
        });

        Model::extend(function (Model $model) {
            $model->addDynamicProperty('_retried_at', null);
            $model->addDynamicMethod('hasBeenSavedSinceRetrieval', function () use ($model) {
                return $model->exists && Carbon::parse($model->_retrieved_at)->isBefore($model->updated_at);
            });
            if (!post('_kpolicar_backendtrafficcop_confirmed')) {
                $model->bindEventOnce('model.saveInternal', function ($attributes, $options) use ($model) {
                    throw_if(
                        $model->hasBeenSavedSinceRetrieval(),
                        ModelHasChangedException::class
                    );
                });
            }
        });
    }
}
