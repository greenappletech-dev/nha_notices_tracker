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
                    <label for="">Search Beneficiary</label>
                    <Select2 v-model="dataValues.beneficiary_id" :options="beneficiaries" class="form-contol" />
                </div>
                
                <!-- Camera Section -->
                <div class="camera-section text-center mt-4">
                    <video ref="camera" autoplay class="border rounded"></video>
                    <br>
                    <button class="btn btn-primary mt-2" @click="capturePhoto">Capture Photo</button>
                    <canvas ref="canvas" class="d-none"></canvas>
                    <div v-if="capturedPhotoURL" class="mt-3">
                        <h5>Captured Image:</h5>
                        <img :src="capturedPhotoURL" class="img-fluid rounded border" />
                    </div>
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
export default {
    props: ['districts'],
    data() {
        return {
            dataValues: {
                notice_id: '',
                district_id: '',
                project_id: '',
                beneficiary_id: '',
            },
            projectList: [],
            beneficiaries: [],
            capturedPhotoURL: '',
            photo: null
        };
    },
    components: { Select2 },
    mounted() {
        this.startCamera();
    },
    methods: {
        selectDistrict() {
            axios.get('deliveries/gather_project/' + this.dataValues.district_id)
                .then(response => {
                    this.projectList = response.data.data;
                });
        },
        myChangeProject() {
            axios.get('deliveries/gather_beneficiaries/' + this.dataValues.project_id)
                .then(response => {
                    this.beneficiaries = response.data.data;
                });
        },
        async capturePhoto() {
            const canvas = this.$refs.canvas;
            const context = canvas.getContext('2d');
            const video = this.$refs.camera;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            this.capturedPhotoURL = canvas.toDataURL('image/png');
            const blob = await fetch(this.capturedPhotoURL).then(res => res.blob());
            this.photo = blob;
        },
        async startCamera() {
            try {
                const videoElement = this.$refs.camera;
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                videoElement.srcObject = stream;
            } catch (error) {
                console.error("Error accessing the camera:", error);
                alert("Unable to access the camera. Please check your browser permissions.");
            }
        },
        storeData() {
            let formData = new FormData();
            formData.append('notice_id', this.dataValues.notice_id);
            formData.append('district_id', this.dataValues.district_id);
            formData.append('project_id', this.dataValues.project_id);
            formData.append('beneficiary_id', this.dataValues.beneficiary_id);
            if (this.photo) {
                formData.append('photo', this.photo, 'captured_photo.png');
            }
            
            axios.post('deliveries/store', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
            });
        }
    }
};
</script>

<style scoped>
.camera-section video {
    width: 100%;
    max-width: 400px;
}
.camera-section img {
    width: 100%;
    max-width: 400px;
}
</style>
