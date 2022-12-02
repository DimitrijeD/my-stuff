<template>
    <div class="default-group-card space-y-2 pt-2 flex flex-rows blue-glow">
        <div class="flex items-center">
            <div class="flex-none">
                <slot name="inviter"></slot>
            </div>
            <span class="pl-2 italic">whishes too add you to 
                <span v-if="name" class="break-all text-blue-500 not-italic">{{ name }}</span> 
            chat group</span>
        </div>

        <div class="grid grid-cols-2 gap-4 grow">
            <slot name="accept"></slot>
            <slot name="decline"></slot>
        </div>
    </div>
</template>

<script>

export default{
    props: ['name']
}
</script>