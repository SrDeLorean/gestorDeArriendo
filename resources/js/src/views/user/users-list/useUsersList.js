import { ref, watch, computed } from '@vue/composition-api'
import axios from 'axios'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useUsersList() {
    // Use toast
    const toast = useToast()

    const refUserListTable = ref(null)

    // Table Handlers
    const tableColumns = [
        { key: 'id', sortable: true },
        { key: 'fullName', sortable: true },
        { key: 'email', sortable: true },
        { key: 'role', sortable: true },
        { key: 'delete', sortable: true },
        { key: 'actions' },
    ]
    const perPage = ref(10)
    const totalUsers = ref(0)
    const currentPage = ref(1)
    const perPageOptions = [10, 25, 50, 100]
    const searchQuery = ref('')
    const sortBy = ref('id')
    const isSortDirDesc = ref(false)
    const roleFilter = ref(null)
    const planFilter = ref(null)
    const statusFilter = ref(null)

    const dataMeta = computed(() => {
        const localItemsCount = refUserListTable.value ? refUserListTable.value.localItems.length : 0
        return {
            from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
            to: perPage.value * (currentPage.value - 1) + localItemsCount,
            of: totalUsers.value,
        }
    })

    const refetchData = () => {
        refUserListTable.value.refresh()
    }

    watch([currentPage, perPage, searchQuery, roleFilter, planFilter, statusFilter], () => {
        refetchData()
    })

    const fetchUsers = (ctx, callback) => {
        var url = 'http://127.0.0.1:8000/api/usuariosConFiltro';
        axios.post(url, {
                searchQuery: searchQuery.value,
                perPage: perPage.value,
                currentPage: currentPage.value,
                sortBy: sortBy.value,
                sortDesc: isSortDirDesc.value,
                role: roleFilter.value,
                plan: planFilter.value,
                status: statusFilter.value,
            })
            .then(response => {
                console.log("q", searchQuery.value)
                console.log("perPage", perPage.value)
                console.log("page", currentPage.value)
                console.log("sortBy", sortBy.value)
                console.log("sortDesc", isSortDirDesc.value)
                console.log("role", roleFilter.value)
                console.log("plan", planFilter.value)
                console.log("status", statusFilter.value)
                console.log("llego", response.data)
                let usuarios = response.data
                const users = usuarios[0].data
                const total = usuarios[0].total
                callback(users)
                totalUsers.value = total
            })
            .catch(() => {
                toast({
                    component: ToastificationContent,
                    props: {
                        title: 'Error fetching users list',
                        icon: 'AlertTriangleIcon',
                        variant: 'danger',
                    },
                })
            })
    }

    // *===============================================---*
    // *--------- UI ---------------------------------------*
    // *===============================================---*

    const resolveUserRoleVariant = role => {
        if (role === 'client') return 'primary'
        if (role === 'admin') return 'success'
        if (role === 'desarrollador') return 'danger'
        return 'primary'
    }

    const resolveUserRoleIcon = role => {
        if (role === 'client') return 'UserIcon'
        if (role === 'admin') return 'DatabaseIcon'
        if (role === 'desarrollador') return 'ServerIcon'
        return 'UserIcon'
    }

    const resolveUserStatusVariant = status => {
        if (status === 'pending') return 'warning'
        if (status === 'active') return 'success'
        if (status === 'inactive') return 'secondary'
        return 'primary'
    }

    return {
        fetchUsers,
        tableColumns,
        perPage,
        currentPage,
        totalUsers,
        dataMeta,
        perPageOptions,
        searchQuery,
        sortBy,
        isSortDirDesc,
        refUserListTable,

        resolveUserRoleVariant,
        resolveUserRoleIcon,
        resolveUserStatusVariant,
        refetchData,

        // Extra Filters
        roleFilter,
        planFilter,
        statusFilter,
    }
}