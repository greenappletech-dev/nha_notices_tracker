<template>
    <div>
        <div class="d-flex justify-content-center">
            <h1>Capture Delivery</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Select Type of Notice</label>
                    <select name="" id="" class="form-control" v-model="dataValues.notice_id">
                        <option value="1">Billing Notice</option>
                        <option value="2">Demand Notice</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Select District</label>
                    <select name="" id="" class="form-control" @change="selectDistrict" v-model="dataValues.district_id">
                        <option v-for="district in districts" :value="district.id">{{ district.name }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Select Project</label>
                    <Select2 v-model="dataValues.project_id" :options="projectList" @change="myChangeProject" class="form-contol"/>
                </div>
                <div class="form-group">
                    <label for="">Search Benefiary</label>
                    <Select2 v-model="dataValues.beneficiary_id" :options="beneficiaries" class="form-contol" />
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button class="btn btn-primary" @click="storeData">Save</button>
            </div>
        </div>
    </div>
</template>
<script>
    import Select2 from 'v-select2-component';
    export default{
        props:['districts'],
        data() {
            return {
                myValue:'',
                myOptions: ['op1', 'op2', 'op3'], // or [{id: key, text: value}, {id: key, text: value}]
                dataValues:{
                    notice_id: '',
                    district_id: '',
                    project_id: '',
                    beneficiary_id:'',
                    photo: null,
                },
                projectList:[],
                beneficiaries:[],
            }
        },
        components: {Select2},
        methods: {
            selectDistrict(){
                axios.get('deliveries/gather_project/'+ this.dataValues.district_id)
                .then(response => {
                    this.projectList = response.data.data;
                 })
            },
            myChangeProject(){
                axios.get('deliveries/gather_beneficiaries/' + this.dataValues.project_id)
                .then(response => {
                    console.log(response.data.data);
                    this.beneficiaries = response.data.data;
                 })
            },
            storeData(){
                axios.post('deliveries/store', this.dataValues)
                .then(response => {
                    console.log(response);
                 })
                .catch(error => {
                    console.log(error);
                 });
            }
            // mySelectProject({id, text}){
            //     console.log({id, text})
            // },
            // myChangeEvent(val){
            //     console.log(val);
            // },
            // mySelectEvent({id, text}){
            //     console.log({id, text})
            // }
        }
    }
</script>