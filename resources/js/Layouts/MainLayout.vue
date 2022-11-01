<template>
    <div class="min-h-screen max-h-screen flex flex-col scroll1 lg:px-2
        bg-gray-200 text-gray-600 
        dark:bg-black dark:text-gray-300"
    >
        <slot name="nav"></slot>
        <main class="flex grow">
            <slot name="view"></slot>
        </main>
    </div>
</template>

<script>

export default{

}
</script>