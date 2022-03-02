import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import axios from 'axios'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useInvoicesList() {
    // Use toast
    const toast = useToast()

    const refInvoiceListTable = ref(null)

    // Table Handlers
    const tableColumns = [
        { key: 'id', label: '#', sortable: true },
        { key: 'get_usuario', label: 'Cliente', sortable: true },
        { key: 'dia', label: 'Dia', sortable: true },
        { key: 'bloques', label: 'Bloques', sortable: true },
        { key: 'inicio', label: 'Inicio', sortable: true },
        { key: 'termino', label: 'Termino', sortable: true },
        { key: 'get_estado', label: 'Estado', sortable: true },
        { key: 'total', label: 'Total', sortable: true, formatter: val => `$${val}` },
        { key: 'created_at', label: 'Registrada', sortable: true },
    ]
    const perPage = ref(10)
    const totalInvoices = ref(0)
    const currentPage = ref(1)
    const perPageOptions = [10, 25, 50, 100]
    const searchQuery = ref('')
    const sortBy = ref('id')
    const isSortDirDesc = ref(true)
    const statusFilter = ref(null)

    const dataMeta = computed(() => {
        const localItemsCount = refInvoiceListTable.value ? refInvoiceListTable.value.localItems.length : 0
        return {
            from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
            to: perPage.value * (currentPage.value - 1) + localItemsCount,
            of: totalInvoices.value,
        }
    })

    const refetchData = () => {
        refInvoiceListTable.value.refresh()
    }

    watch([currentPage, perPage, searchQuery, statusFilter], () => {
        refetchData()
    })

    const fetchInvoices = (ctx, callback) => {
        console.log(perPage.value)
        console.log(totalInvoices.value)
        console.log(currentPage.value)
        console.log(searchQuery.value)
        console.log(sortBy.value)
        console.log(isSortDirDesc.value)
        var url = 'http://127.0.0.1:8000/api/comprobanteConFiltro';
        axios.post(url, {
                searchQuery: searchQuery.value,
                perPage: perPage.value,
                currentPage: currentPage.value,
                sortBy: sortBy.value,
                sortDesc: isSortDirDesc.value,
            })
            .then(response => {
                console.log(response.data)
                let reservas = response.data
                const invoices = reservas[0].data
                const total = reservas[0].total
                callback(invoices)
                totalInvoices.value = total
            })
            .catch(() => {
                toast({
                    component: ToastificationContent,
                    props: {
                        title: "Error fetching invoices' list",
                        icon: 'AlertTriangleIcon',
                        variant: 'danger',
                    },
                })
            })
    }

    // *===============================================---*
    // *--------- UI ---------------------------------------*
    // *===============================================---*

    const resolveInvoiceStatusVariantAndIcon = status => {
        if (status === 'Partial Payment') return { variant: 'warning', icon: 'PieChartIcon' }
        if (status === 'Paid') return { variant: 'success', icon: 'CheckCircleIcon' }
        if (status === 'Downloaded') return { variant: 'info', icon: 'ArrowDownCircleIcon' }
        if (status === 'Draft') return { variant: 'primary', icon: 'SaveIcon' }
        if (status === 'Sent') return { variant: 'secondary', icon: 'SendIcon' }
        if (status === 'Past Due') return { variant: 'danger', icon: 'InfoIcon' }
        return { variant: 'secondary', icon: 'XIcon' }
    }

    const resolveClientAvatarVariant = status => {
        if (status === 'Partial Payment') return 'primary'
        if (status === 'Paid') return 'danger'
        if (status === 'Downloaded') return 'secondary'
        if (status === 'Draft') return 'warning'
        if (status === 'Sent') return 'info'
        if (status === 'Past Due') return 'success'
        return 'primary'
    }

    return {
        fetchInvoices,
        tableColumns,
        perPage,
        currentPage,
        totalInvoices,
        dataMeta,
        perPageOptions,
        searchQuery,
        sortBy,
        isSortDirDesc,
        refInvoiceListTable,

        statusFilter,

        resolveInvoiceStatusVariantAndIcon,
        resolveClientAvatarVariant,

        refetchData,
    }
}