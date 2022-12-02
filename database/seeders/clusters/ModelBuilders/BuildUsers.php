<?php

namespace Database\Seeders\clusters\ModelBuilders;

use App\Models\User;
use App\Models\UserSettings;
use Database\Factories\UserFactory;
use App\Models\Chat\ChatRole;

class BuildUsers
{
    public $participants, $numUsers, $users;

    public function __construct($participants = [], $numUsers = 2)
    {
        $this->participants = count($participants) > 1 ? $participants : $this->useDefault();
        $this->numUsers = $numUsers; 
        $this->users = collect([]);
    }

    public function resolve()
    {
        if( !($this->numUsers - count($this->participants) <= 0 ) ){
            // there is extra space for random users
            $this->users = $this->users->merge( $this->makeRemainingRandomUsers( $this->numUsers - count($this->participants) ));
        } 

        return $this;
    }

    public function build()
    {
        foreach($this->participants as $participant){
            // if user with that email exists add him, otherwise create
            if( !$user = User::where('email', $participant['email'])->first() ) {

                $user = User::factory()->has(UserSettings::factory())->create([
                    'first_name' => $participant['first_name'], 
                    'last_name' => $participant['last_name'], 
                    'email' => $participant['email'], 
                ]);
            }

            $user->participant_role = $this->getUserRoleFrom_setter_participants($user->email);
            $this->users->push( $user );
        }

        return $this->users;
    }

    private function makeRemainingRandomUsers($num)
    {
        if(!$num) return []; 
        
        $users = User::factory($num)->has(UserSettings::factory())->create();

        return $this->attachParticipantRolesToRandomUsers($users, ChatRole::PARTICIPANT);        
    }

    private function useDefault()
    {
        return [
            array_merge(UserFactory::getDefUser(), ['participant_role' => ChatRole::CREATOR]),

            [
                'first_name' => 'Asd',
                'last_name' => 'Asd',
                'email' => 'asd@asd', 
                'participant_role' => ChatRole::PARTICIPANT
            ],
        ];
    }

    //xd
    private function getUserRoleFrom_setter_participants($email)
    {
        foreach($this->participants as $participant){
            if($participant['email'] == $email){
                return $participant['participant_role'];
            }
        }

        return ChatRole::PARTICIPANT;
    }

    private function attachParticipantRolesToRandomUsers($users, $role)
    {
        foreach($users as $user){
            $user->participant_role = $role;
        }

        return $users;
    }

}