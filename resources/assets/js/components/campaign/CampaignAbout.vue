<template lang="html">
    <div class="row">
        <div class="col-xl-6 push-xl-3 col-lg-12 push-lg-0 col-sm-12 col-xs-12">
            <div class="ui-block">
                <article class="hentry post">
                    <div id="newsfeed-items-grid" v-html="campaign.description"></div>
                </article>
            </div>
        </div>

        <left-campaign ></left-campaign>
        <right-campaign></right-campaign>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import LeftCampaign from './LeftCampaign.vue'
import RightCampaign from './RightCampaign.vue'

export default {
    computed: {
        ...mapState('campaign', [
            'campaign',
            'events',
            'loading',
            'pageNumberEvent',
            'pageCurrentEvent'
        ]),
    },
    mounted() {
        $(window).scroll(() => {
            if ($(document).height() <= $(window).scrollTop() + $(window).height()) {
                this.loadMore()
            }
        })
    },
    methods: {
        ...mapActions('campaign', ['fetchData']),
        loadMore() {
            var data = {
                campaignId: this.pageId,
                events: this.events,
                pageNumberEvent: this.pageNumberEvent,
                pageCurrent: this.pageCurrentEvent
            }

            this.fetchData(data)
        }
    },
    components: {
        LeftCampaign,
        RightCampaign
    }
}
</script>

<style lang="scss">
</style>
