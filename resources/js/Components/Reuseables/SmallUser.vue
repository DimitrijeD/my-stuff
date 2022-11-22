<template>
    <div v-show="user" :class="[static.layout, getLayoutCls]" >
        <img
            :src="user?.thumbnail"
            :class="[static.img, getImgCls]"
            alt="no img :/"
        > 
        <p :class="[static.userName, getUserNameCls]">
            {{ getFullName }}
        </p>
    </div>
</template>

<script>
export default {
    props:[ 'user', 'layoutCls', 'imgCls', 'userNameCls', 'showOnly', ],

    computed: {
        getLayoutCls(){ return this.layoutCls ? this.layoutCls : this.default.layout },

        getImgCls(){ return this.imgCls ? this.imgCls : this.default.img },

        getUserNameCls(){ return this.userNameCls ? this.userNameCls : this.default.userName },

        // expects "first_name", "last_name" or ""
        getFullName(){ 
            if(this.user?.defaultUser) return this.user.name
            
            if(!this.showOnly) return `${this.user?.first_name} ${this.user?.last_name}`

            return this.user[this.showOnly]
        }
    },

    data() {
        return {
            default:{ 
                layout: "",
                img: "w-16 h-16 select-none",
                userName: "truncate ",
            },

            static: {
                layout: " flex items-center cursor-pointer space-x-1 ",
                img: "inline-block ml-0.5 object-cover border border-gray-400 dark:border-gray-600 rounded-full ",
                userName: "inline-block truncate",
            },
        }
    },


}
</script>
