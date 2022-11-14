<template>
    <div class="space-y-4">
        <div class="flex space-x-4">
            <label class="my-auto">Open chat on new message</label>
            <CheckBoxInput 
                :boxCls="'fill-gray-600 dark:fill-gray-500/80'" 
                class="w-10 h-10 " 
                :name="'open_all_chats_on_new_message'" 
                :value="form.settingsFiels.open_all_chats_on_new_message" 
                @inputC="save" 
            />
        </div>

        <div class="flex space-x-4">
            <label class="my-auto">Show only important notifications</label>
            <CheckBoxInput 
                :boxCls="'fill-gray-600 dark:fill-gray-500/80'" 
                class="w-10 h-10 " 
                :name="'show_only_important_notifications'" 
                :value="form.settingsFiels.show_only_important_notifications" 
                @inputC="save" 
            />
        </div>
    </div>
</template>

<script>
import CheckBoxInput from '@/Components/Reuseables/CheckBoxInput.vue'

export default {
    props: ['user'],

    components: { CheckBoxInput, },

    data(){
        return {
            anyChanges: false,
            form:{ 
                settingsFiels:{
                    open_all_chats_on_new_message: null,
                    show_only_important_notifications: null,
                }
            },
        }
    },

    mounted(){
        this.resetForm()
    },

    watch: {
        form(){

        }
    },

    methods: {
        resetForm(){
            this.form = {
                settingsFiels:{
                    open_all_chats_on_new_message:     this.user.user_setting.open_all_chats_on_new_message ,
                    show_only_important_notifications: this.user.user_setting.show_only_important_notifications,
                }
            }
        },

        save(data){
            this.form.settingsFiels[data.key] = !this.form.settingsFiels[data.key]

            this.$store.dispatch('updateProfile', this.form).then(() => {
                this.resetForm()
            })
        },

    },

    
}
</script>