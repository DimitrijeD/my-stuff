<template>
    <div class="def-trans min-h-screen max-h-screen flex flex-col overflow-hidden
        bg-white text-gray-800 
        dark:bg-black dark:text-gray-300"
    >
        <div>
            <slot name="nav"></slot>
        </div>
        <main class="flex grow scroll1">
            <slot name="view"></slot>
        </main>
    </div>
</template>

<script>

export default{

}
</script>
