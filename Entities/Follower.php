<?php

namespace Modules\Ifollow\Entities;

use Astrotomic\Translatable\Translatable;
use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Notification\Traits\IsNotificable;

class Follower extends CrudModel
{

    use IsNotificable;

    protected $table = 'ifollow__followers';
    public $transformer = 'Modules\Ifollow\Transformers\FollowerTransformer';
    public $requestValidation = [
        'create' => 'Modules\Ifollow\Http\Requests\CreateFollowerRequest',
        'update' => 'Modules\Ifollow\Http\Requests\UpdateFollowerRequest',
        'delete' => 'Modules\Ifollow\Http\Requests\DeleteFollowerRequest',
      ];

    protected $fillable = [
        'follower_id',
        'followable_id',
        'followable_type',
    ];

    public function followable()
    {
        return $this->morphTo();
    }

    public function user(){
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User","follower_id");
    }

    /**
     * Make Notificable Params | to Trait
     * @param $event (created|updated|deleted)
     */
    public function isNotificableParams($event)
    {

        //Get Emails and Broadcast
        $followerService = app("Modules\Ifollow\Services\FollowerService");
        $result = $followerService->getEmailsAndBroadcast($this);

        return [
            'created' => [
                "title" => trans("ifollow::common.follow.created.title"),
                "message" =>  trans("ifollow::common.follow.created.message",['user' => $result['createdByUser']]),
                "email" => $result['email'],
                "broadcast" => $result['broadcast']
            ],
            'deleted' => [
                "title" => trans("ifollow::common.follow.deleted.title"),
                "message" =>  trans("ifollow::common.follow.deleted.message",['user' => $result['createdByUser']]),
                "email" => $result['email'],
                "broadcast" => $result['broadcast']
            ],
        ];

    }

}
