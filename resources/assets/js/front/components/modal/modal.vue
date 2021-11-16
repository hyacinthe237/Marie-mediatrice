<template lang="html">
    <div class="_modal">
        <div class="modal fade" :id="slug" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-heading">
                        <span class="pull-right">
                            <i class="ion-close" data-dismiss="modal"></i>
                        </span>
                        {{ title }}
                    </div>

                    <div class="modal-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'modal',
    props: {
        title: {
            type: String,
            default: 'Modal'
        },
        slug: {
            type: String,
            default: 'Modal'
        }
    },
}
</script>
