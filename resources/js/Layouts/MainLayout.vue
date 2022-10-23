<template>
    <div class="min-h-screen max-h-screen flex flex-col px-2 scroll1
        bg-gray-200 text-gray-600 
        dark:bg-black dark:text-gray-300"
    >
        <slot name="nav"></slot>
        <slot name="view"></slot>
    </div>
</template>

<script>

export default{

}
</script>