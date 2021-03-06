/* ============
 * Getters for the account module
 * ============
 *
 * The getters that are available on the
 * account module.
 */
export default {
    checkPermission(state, getters) {
        if ($.isEmptyObject(state.campaign.check_owner) && $.isEmptyObject(state.campaign.check_moderators)) {
            return false
        }

        return true
    },

    checkJoinCampaign(state, getters) {
        if ($.isEmptyObject(state.campaign.check_status)) {
            return 1 //join campaign
        }

        if (state.campaign.check_status.pivot.status == 0) {
            return 2 //approving
        }

        if (state.campaign.check_status.pivot.status == 2) {
            return 4 //invting
        }

        return 3 //approved
    },

    checkOwner(state, getters) {
        if ($.isEmptyObject(state.campaign.check_owner)) {
            return false
        }

        return true
    },

    checkModerators(state, getters) {
        if ($.isEmptyObject(state.campaign.check_moderators)) {
            return false
        }

        return true
    },
};
