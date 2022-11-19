<template>
    <div class="w-full h-full select-none overflow-hidden rounded flex flex-row place-items-center h-full gap-2 py-1 pl-1">
        <slot name="question"></slot>
        <div class="ml-auto py-2 gap-2 flex flex-row">
            <slot name="accept"></slot>
            <slot name="decline"></slot>
        </div>
    </div>
</template>

<script>
export default{

}
</script>