<template>
    <div class="space-y-10">
        <h1>Icons Examples</h1>
        <div class="mt-10 grid grid-cols-12 gap-2">
            <div class="item">
                <SettingsIcon class="test"  />
                <span class="span">Settings Icon</span>
            </div>
            <div class="item">
                <ArrowDropIcon class="test" />
                <span class="span">Arrow Drop Icon</span>
            </div>
            
            <div class="item">
                <DeleteIcon class="test" />
                <span class="span">Delete Icon</span>
            </div>
            <div class="item">
                <CheckCyrcleIcon class="test" />
                <span class="span">Check Cyrcle Icon</span>
            </div>
            <div class="item">
                <ThreeHorisontalParalelLinesIcon class="test" />
                <span class="span">Three Horisontal Paralel LinesIcon</span>
            </div>
            <div class="item">
                <SearchIcon class="test" />
                <span class="span">Search Icon</span>
            </div>
            <div class="item">
                <ThreeDotsIcon class="test" />
                <span class="span">Three Dots Icon</span>
            </div>
            <div class="item">
                <RefreshIcon class="test" />
                <span class="span">Refresh Icon</span>
            </div>
            <div class="item">
                <OpenInFullIcon class="test" />
                <span class="span">Open In Full Icon</span>
            </div>
            <div class="item">
                <FullScreenExitIcon class="test" />
                <span class="span">Full Screen Exit Icon</span>
            </div>
            <div class="item">
                <FullScreenEnterIcon class="test" />
                <span class="span">Full Screen Enter Icon</span>
            </div>
            <div class="item">
                <DoubleArrowIcon class="test" />
                <span class="span">Double Arrow Icon</span>
            </div>
            <div class="item">
                <CloseFullScreenIcon class="test" />
                <span class="span">Close Full Screen Icon</span>
            </div>
            <div class="item">
                <ArrowHeadIcon class="test" />
                <span class="span">Arrow Head Icon</span>
            </div>
            <div class="item">
                <AddIcon class="test" />
                <span class="span">Add Icon</span>
            </div>
            <div class="item">
                <DeclineIcon class="test" />
                <span class="span">Decline Icon</span>
            </div>
            <div class="item">
                <AcceptIcon class="test" />
                <span class="span">Accept Icon</span>
            </div>
            <div class="item">
                <BackIcon class="test" />
                <span class="span">Back Icon</span>
            </div>

            <div class="item">
                <SendMessageIcon class="test" />
                <span class="span">Send Message Icon</span>
            </div>

            <div class="item">
                <KeyIcon class="test" />
                <span class="span">Key Icon</span>
            </div>

            <div class="item">
                <CyrcleIcon class="test" />
                <span class="span">Key Icon</span>
            </div>

            <div class="item">
                <DiagonalArrowIcon class="test" />
                <span class="span">DiagonalArrow Icon</span>
            </div>
            <div class="item">
                <MailIcon class="test" />
                <span class="span">MailIcon Icon</span>
            </div>
            <div class="item">
                <DoneIcon class="test" />
                <span class="span">DoneIcon Icon</span>
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

DoneIcon

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
    },

    data(){
        return {
            
        }
    }
}
</script>


<style scoped>
.test {
    @apply mx-auto w-[80%] h-[80%]
    bg-gray-300 fill-red-400 stroke-blue-600
    dark:bg-darker-100 dark:fill-gray-200 dark:stroke-blue-500;
}

.span {
    @apply mx-auto px-2 py-0.5 font-extralight text-sm text-gray-400 dark:text-gray-400 ;
}

.item {@apply flex flex-col border border-gray-300 dark:border-darker-200  ;}

</style>