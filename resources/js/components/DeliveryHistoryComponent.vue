<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <h2 class="mb-4">Delivery Viewer</h2>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <v-client-table
                :data="tableData"
                :columns="columns"
                :options="options"
                >
                <template slot="actions" slot-scope="{row}">
                    <button class="btn btn-primary">View</button>
                </template>
                </v-client-table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:['deliveries'],
        data(){
            return {
                tableData: this.deliveries,
                columns:['notice_type_name', 'project_name', 'address', 'beneficiary_name', 'date_captured', 'collector_name', 'actions'],
                options:{
                    headings:{
                        notice_type_id: 'Notice Type',
                        project_name: 'Project Name',
                        beneficiary_name: 'Beneficiary Name',
                        date_captured: 'Date Captured',
                        collector_name: 'Collector Name',
                        actions: 'Actions'
                    }
                }
            }
        }
    }
</script>