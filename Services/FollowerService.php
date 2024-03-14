<?php

namespace Modules\Ifollow\Services;

class FollowerService
{

    /**
    * Get emails and broadcast information
    */
    public function getEmailsAndBroadcast($entity)
    {
        
        $emailTo = [];
        $broadcastTo = [];

        $followable = $entity->followable;
        $createdByUser = $entity->user->present()->fullname;

        //Case Organization
        if($entity->followable_type=="Modules\Isite\Entities\Organization"){
            $user =  $followable->users->first();
            array_push($emailTo, $user->email);
            array_push($broadcastTo, $user->id);
        }
        
        // Data Notification
        $to["email"] = $emailTo;
        $to["broadcast"] = $broadcastTo;
        $to['createdByUser'] = $createdByUser;
    
        return $to;
    }
    
}
