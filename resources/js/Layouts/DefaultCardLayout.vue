<template>
    <div class="flex w-full h-full flex-col overflow-hidden "> 
        <slot name="header"></slot>

        <div class="grow overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 bottom-0 scroll1 space-y-2">
                <slot name="body"></slot>
            </div>
        </div>

        <slot name="footer"></slot>
    </div>
</template>

<script>

 export default {

}

</script>
