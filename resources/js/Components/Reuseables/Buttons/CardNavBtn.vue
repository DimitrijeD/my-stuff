<template>
    <button :class="[ 'small-nav-btn ', isActive ? 'small-nav-btn-active' : 'small-nav-btn-inactive', ]">
        <slot></slot>
    </button>
</template>

<script>
export default{
    props: ['isActive']
}
</script>