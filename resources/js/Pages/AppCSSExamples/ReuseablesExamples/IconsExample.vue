<template>
    <div class="space-y-10">
        <h1>Icons Examples</h1>
        <div class="mt-10 grid grid-cols-6 md:grid-cols-12 gap-2">
            <div v-for="icon in icons">
                <component :is="icon" class="icon" />
                <p class="span">{{ icon }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import ArrowDropIcon from "@/Components/Reuseables/Icons/ArrowDropIcon.vue"
import DeleteIcon from "@/Components/Reuseables/Icons/DeleteIcon.vue"
import SettingsIcon from "@/Components/Reuseables/Icons/SettingsIcon.vue"
import CheckCyrcleIcon from "@/Components/Reuseables/Icons/CheckCyrcleIcon.vue"
import ThreeHorisontalParalelLinesIcon from "@/Components/Reuseables/Icons/ThreeHorisontalParalelLinesIcon.vue"
import ThreeDotsIcon from "@/Components/Reuseables/Icons/ThreeDotsIcon.vue"
import SearchIcon from "@/Components/Reuseables/Icons/SearchIcon.vue"
import RefreshIcon from "@/Components/Reuseables/Icons/RefreshIcon.vue"
import OpenInFullIcon from "@/Components/Reuseables/Icons/OpenInFullIcon.vue"
import FullScreenExitIcon from "@/Components/Reuseables/Icons/FullScreenExitIcon.vue"
import FullScreenEnterIcon from "@/Components/Reuseables/Icons/FullScreenEnterIcon.vue"
import DoubleArrowIcon from "@/Components/Reuseables/Icons/DoubleArrowIcon.vue"
import CloseFullScreenIcon from "@/Components/Reuseables/Icons/CloseFullScreenIcon.vue"
import ArrowHeadIcon from "@/Components/Reuseables/Icons/ArrowHeadIcon.vue"
import AddIcon from "@/Components/Reuseables/Icons/AddIcon.vue"
import DeclineIcon from "@/Components/Reuseables/Icons/DeclineIcon.vue"
import AcceptIcon from "@/Components/Reuseables/Icons/AcceptIcon.vue"
import BackIcon from "@/Components/Reuseables/Icons/BackIcon.vue"
import SendMessageIcon from "@/Components/Reuseables/Icons/SendMessageIcon.vue"
import CyrcleIcon from "@/Components/Reuseables/Icons/CyrcleIcon.vue"
import DiagonalArrowIcon from "@/Components/Reuseables/Icons/DiagonalArrowIcon.vue"
import KeyIcon from "@/Components/Reuseables/Icons/KeyIcon.vue"
import MailIcon from "@/Components/Reuseables/Icons/MailIcon.vue"
import DoneIcon from "@/Components/Reuseables/Icons/DoneIcon.vue"
import CheckBoxBlancIcon from "@/Components/Reuseables/Icons/CheckBoxBlancIcon.vue"
import CheckBoxCheckIcon from "@/Components/Reuseables/Icons/CheckBoxCheckIcon.vue"
import NumbersIcon from "@/Components/Reuseables/Icons/NumbersIcon.vue"
import InfoIcon from "@/Components/Reuseables/Icons/InfoIcon.vue"
import LoadingIcon from "@/Components/Reuseables/Icons/LoadingIcon.vue"
import LockOpenIcon from "@/Components/Reuseables/Icons/LockOpenIcon.vue"
import LockClosedIcon from "@/Components/Reuseables/Icons/LockClosedIcon.vue"


export default {
    components:{
        ArrowDropIcon,
        DeleteIcon,
        SettingsIcon,
        CheckCyrcleIcon,
        ThreeHorisontalParalelLinesIcon,
        ThreeDotsIcon,
        SearchIcon,
        RefreshIcon,
        OpenInFullIcon,
        FullScreenExitIcon,
        FullScreenEnterIcon,
        DoubleArrowIcon,
        CloseFullScreenIcon,
        ArrowHeadIcon,
        AddIcon,
        DeclineIcon,
        AcceptIcon,
        BackIcon,
        SendMessageIcon,
        KeyIcon,
        CyrcleIcon,
        DiagonalArrowIcon,
        MailIcon,
        DoneIcon,
        CheckBoxBlancIcon,
        CheckBoxCheckIcon,
        NumbersIcon,
        InfoIcon,
        LoadingIcon,
        LockOpenIcon,
        LockClosedIcon,
    },

    data(){
        return {
            icons: [
                'ArrowDropIcon',
                'DeleteIcon',
                'SettingsIcon',
                'CheckCyrcleIcon',
                'ThreeHorisontalParalelLinesIcon',
                'ThreeDotsIcon',
                'SearchIcon',
                'RefreshIcon',
                'OpenInFullIcon',
                'FullScreenExitIcon',
                'FullScreenEnterIcon',
                'DoubleArrowIcon',
                'CloseFullScreenIcon',
                'ArrowHeadIcon',
                'AddIcon',
                'DeclineIcon',
                'AcceptIcon',
                'BackIcon',
                'SendMessageIcon',
                'KeyIcon',
                'CyrcleIcon',
                'DiagonalArrowIcon',
                'MailIcon',
                'DoneIcon',
                'CheckBoxBlancIcon',
                'CheckBoxCheckIcon',
                'NumbersIcon',
                'InfoIcon',
                'LoadingIcon',
                'LockOpenIcon',
                'LockClosedIcon',
            ]
        }
    }
}
</script>


<style scoped>
.icon {
    @apply mx-auto w-[80%] h-[80%]
    bg-gray-300 fill-red-400 stroke-blue-600
    dark:bg-darker-100 dark:fill-gray-200 dark:stroke-blue-500;
}

.span {
    @apply mx-auto break-words p-2 font-extralight text-sm text-gray-400 dark:text-gray-400 ;
}

.item {@apply flex flex-col border border-gray-300 dark:border-darker-200  ;}

</style>