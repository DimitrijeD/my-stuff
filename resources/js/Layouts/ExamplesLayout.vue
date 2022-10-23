<template>
    <div class="mb-10 space-y-4 ">
        <slot name="layout-name"></slot>
        <div>
            <h2>Info</h2>
            <ul class="examples-ul">
                <slot name="info-ul"></slot>
            </ul>
        </div>

        <div>
            <h2>Issues</h2>
            <ul class="examples-ul">
                <slot name="issues-ul"></slot>
            </ul>
        </div>

        <hr class="border-gray-500 dark:border-darker-400">
    </div>

    <div class="space-y-10 p-2">
        <slot name="body"></slot>
    </div>
</template>

<script>
    // <ExamplesLayout>
    //     <template #layout-name>

    //     </template>

    //     <template #info-ul>

    //     </template>

    //     <template #issues-ul>

    //     </template>

    //     <template #body>

    //     </template>
    // </ExamplesLayout>
</script>
