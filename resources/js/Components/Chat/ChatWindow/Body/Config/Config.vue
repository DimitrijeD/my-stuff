<template>
    <FillRemainingSpaceLayout>
        <template #header>
            <ul class="flex" > 
                <li v-for="(setting, key) in settings" :key="key" @click="openSetting(key)"
                    :class="[ 'small-nav-btn flex-1', setting.opened ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]">
                    {{ setting.forHuman }} 
                </li>
            </ul>
        </template>

        <template #content>
            <div class="h-full p-2 text-gray-600 dark:text-gray-300 font-light relative">
                <ActionResponseList 
                    :moduleId="`config.groupId_${group.id}`" 
                    :dieAfter="15" 
                    :cardCls="'w-[70%] h-24 mx-auto'"
                    class="absolute z-10 mx-auto w-full" 
                />

                <KeepAlive>
                    <component :is="openedComponent"
                        :group="group" 
                        :permissions="permissions" 
                    />
                </KeepAlive>

                <!-- 
                    Participants : 'group', 'permissions', 
                    AddUsers: 'group', 'permissions'
                    Info: 'group', 'permissions'
                    Options: 'group', 'permissions'
                 -->
            </div>
        </template>
    </FillRemainingSpaceLayout>
</template>

<script>
import Participants from '@/Components/Chat/ChatWindow/Body/Config/Participants/Participants.vue'
import Info         from '@/Components/Chat/ChatWindow/Body/Config/Info.vue'
import Options      from '@/Components/Chat/ChatWindow/Body/Config/Options/Options.vue'
import AddUsers     from '@/Components/Chat/ChatWindow/Body/Config/AddUsers.vue'

import FillRemainingSpaceLayout from '@/Layouts/FillRemainingSpaceLayout.vue'
import ActionResponseList from '@/Components/ActionResponse/ActionResponseList.vue';
import * as ns from '@/Store/module_namespaces.js'

export default {
    props: [ 'group', 'permissions', 'chatRole', 'roles', ],

    components: { AddUsers, Participants, Info, Options, FillRemainingSpaceLayout, ActionResponseList, },

    data(){
        return {
            user: this.$store.state.auth.user,

            allSettingComp: ['AddUsers', 'Participants', 'Info', 'Options'],

            settings: {
                AddUsers: {
                    forHuman: 'Add Users',
                    opened: false, 
                    name: 'AddUsers'
                },

                Info: {
                    forHuman: 'Info',
                    opened: false,
                    name: 'Info'

                },

                Participants: {
                    forHuman: 'Participants',
                    opened: false, 
                    name: 'Participants'
                },

                Options: {
                    forHuman: 'Options',
                    opened: false, 
                    name: 'Options'
                },
            },
        }
    },

    computed: {
        openedComponent(){
            for(let key in this.settings){
                if(this.settings[key].opened){
                    return this.settings[key].name
                }
            }
        }
    },

    watch: {
        permissions: {
            handler: function () {
                this.createPermissibleSettings() // reset UI when users role changes
            },
            deep: true,
        },
    }, 

    created(){
        this.createPermissibleSettings()
        this.setFirstOpenedConfig()
    },

    methods: {
        openSetting(key){
            for(let key in this.settings){
                this.settings[key].opened = false
            }

            this.settings[key].opened = true
        },

        /**
         * Exclue settings from showing in Config nav if their role is not allowing certain action
         */
        createPermissibleSettings(){
            if(this.permissions.add.length){
                if(!this.settings?.AddUsers){
                    this.settings.AddUsers = {
                        forHuman: 'Add Users',
                        opened: false, 
                        name: 'AddUsers'
                    }
                }
            } else {
                delete this.settings.AddUsers
            }

            this.sortNav()
        },

        /**
         * After users permissions have been changed, navbar will change as well. This method sorts navbar to default state
         */
        sortNav(){
            let temp = {}

            for(let i in this.allSettingComp){
                if(this.settings[this.allSettingComp[i]]){
                    temp[ this.allSettingComp[i] ] = this.settings[ this.allSettingComp[i] ]
                }
            }
            
            this.settings = temp
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