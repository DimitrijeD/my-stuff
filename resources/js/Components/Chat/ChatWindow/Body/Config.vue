<template>
    <FillRemainingSpaceLayout>
        <template #header>
            <ul class="flex" > 
                <li v-for="(setting, key) in settings" :key="key" @click="openSetting(key)"
                    :class="[ 'small-nav-btn flex-1', setting.opened ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]">
                    {{ setting.name }} 
                </li>
            </ul>
        </template>

        <template #content>
            <div class="h-full p-2 text-gray-600 dark:text-gray-300 font-light">
                <AddUsers
                    v-show="settings.hasOwnProperty('add_users') && settings.add_users.opened" 
                    :group="group"  
                    :permissions="permissions"  
                />

                <Info 
                    v-show="settings.hasOwnProperty('info') && settings.info.opened" 
                    :group="group" 
                    :permissions="permissions"
                />

                <Participants 
                    v-show="settings.hasOwnProperty('participants') && settings.participants.opened" 
                    :group="group" 
                    :chatRole="chatRole"
                    :permissions="permissions"
                    :roles="roles"
                />

                <Options 
                    v-show="settings.hasOwnProperty('options') && settings.options.opened"
                    :group="group" 
                    :permissions="permissions"
                />
            </div>
        </template>
    </FillRemainingSpaceLayout>
</template>

<script>
import Info         from '@/Components/Chat/ChatWindow/Body/Config/Info.vue'
import Participants from '@/Components/Chat/ChatWindow/Body/Config/Participants.vue'
import Options      from '@/Components/Chat/ChatWindow/Body/Config/Options.vue'
import AddUsers     from '@/Components/Chat/ChatWindow/Body/Config/AddUsers.vue'

import FillRemainingSpaceLayout from '@/Layouts/FillRemainingSpaceLayout.vue'

export default {
    props: [ 'group', 'permissions', 'chatRole', 'roles', ],

    components: {
        AddUsers,
        Participants,
        Info,
        Options,
        FillRemainingSpaceLayout,
    },

    data(){
        return {
            user: this.$store.state.auth.user,

            settings: {
                add_users: {
                    name: 'Add Users',
                    opened: false, 
                },

                info: {
                    name: 'Info',
                    opened: false,
                },

                participants: {
                    name: 'Participants',
                    opened: false, 
                },

                options: {
                    name: 'Options',
                    opened: false, 
                },
            },
        }
    },

    watch: {
        chatRole: function () {
            this.createPermissibleSettings() // reset UI when users role changes
        },
    },

    created(){
        this.createPermissibleSettings()
        this.setFirstOpenedConfig()
    },

    mounted(){
  
    },

    methods: 
    {
        openSetting(key){
            for(let type in this.settings){
                this.settings[type].opened = false
            }

            this.settings[key].opened = true
        },

        /**
         * Exclue settings from showing in Config nav if their role is not allowing certain action
         */
        createPermissibleSettings(){
            if(this.permissions.add.length){
                if(!this.settings.hasOwnProperty('add_users')){
                    this.settings.add_users = {
                        name: 'Add Users',
                        opened: false, 
                    }
                }
            } else {
                if(this.settings.hasOwnProperty('add_users')) delete this.settings.add_users
            }

            if(this.group.model_type == "PRIVATE") delete this.settings['participants']
        },

        setFirstOpenedConfig(){
            let oneWillBeOpened = false

            for(let settingIndex in this.settings){
                if(this.settings[settingIndex].opened){
                    oneWillBeOpened = true
                    break
                }
            }

            // if no settings are opened, then select first one in object as default opened setting after user clicks config cog
            if(!oneWillBeOpened){
                let firstOpened = Object.keys(this.settings)[0]
                this.settings[firstOpened].opened = true
            }
        },

    },
}

</script>

<style>

</style>