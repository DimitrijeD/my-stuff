<template>
    <div class="space-y-8">
        <div class="space-y-4">
            <div class="space-y-1">
                <label class="setting-label">First name</label>
                <input class="setting-input" v-model="form.userFields.first_name"  >
            </div>

            <div class="space-y-1">
                <label class="setting-label">Last name</label>
                <input class="setting-input" v-model="form.userFields.last_name"  >
            </div>

            <div class="space-y-1">
                <p class="setting-label">Email</p>
                <p class="setting-input cursor-not-allowed text-left" >{{ user?.email }}</p>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <button v-if="anyChanges" class="setting-btn-save " @click="save">Save changes</button>
                <button v-if="anyChanges" class="setting-btn-cancel " @click="resetForm">Cancel</button>
            </div>
        </div>

        <button class="setting-btn-do " @click="forgotPasswordRequestEmail">Get Password Reset Email</button>

        <div class="">
            <AreYouSureLayout class="py-10 border border-red-400 dark:border-red-600/50 rounded bg-white dark:bg-transparent">
                <template #button-as-wrapper>
                    <button class="danger-btn ">
                        Delete Account
                    </button>
                </template>
                <template #question>
                    <p>
                        Are you sure you wish to delete this account?
                    </p>
                </template>

                <template #yes>
                    <AcceptIcon @click="deleteAccount" class="h-14 fill-gray-500 py-2 hover:fill-red-500" />
                </template>

                <template #no>
                    <DeclineIcon class="h-14 fill-gray-500 py-2 hover:fill-gray-400" />
                </template>
            </AreYouSureLayout>
        </div>
    </div>
</template>

<script>
import AreYouSureLayout from '@/Layouts/AreYouSureLayout.vue'
import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue"
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"

export default {
    props: ['user'],

    components: { AreYouSureLayout, AcceptIcon, DeclineIcon },

    data(){
        return {
            anyChanges: false,
            form:{ 
                userFields:{
                    first_name: '',
                    last_name: '',
                }
            }
        }
    },

    watch: {
        form: {
            handler(){
                if(this.user.first_name != this.form.userFields.first_name) { 
                    this.anyChanges = true 
                    return
                }
                if(this.user.last_name != this.form.userFields.last_name) { 
                    this.anyChanges = true 
                    return
                }
                
                this.anyChanges =  false
            },
            deep: true
        }
    },

    mounted(){
        this.resetForm()
    },

    methods: {
        forgotPasswordRequestEmail(){
            this.$store.dispatch('forgotPassword', {email: this.user.email})
        },

        deleteAccount(){
            this.$store.dispatch('deleteAccount').then(()=>{
                this.$router.push({ name: 'Home' })
            })
        },

        save(){
            this.$store.dispatch('updateProfile', this.collectInput()).then(()=>{
                this.resetForm()
                this.anyChanges =  false
            })
        },

        resetForm(){
            this.form = {
                userFields:{
                    first_name: this.user.first_name,
                    last_name: this.user.last_name,
                }
            }
        },

        /**
         * Creates object with only updated data
         */
        collectInput(){
            let data = {
                userFields: {},
                settingsFiels: {}
            }

            if(this.user.first_name != this.form.userFields.first_name)  
                data.userFields.first_name = this.form.userFields.first_name

            if(this.user.last_name != this.form.userFields.last_name)  
                data.userFields.last_name = this.form.userFields.last_name

            if(!Object.keys(data.userFields))
                delete data.userFields

            if(Object.keys(data.settingsFiels).length == 0)
                delete data.settingsFiels

            return data
        }
    },

    
}
</script>

<style>

</style>