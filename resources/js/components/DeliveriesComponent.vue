<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-center">
            <h2 class="mb-4">Capture Delivery</h2>
        </div>

        <!-- Success Notification -->
        <div v-if="showSuccessMessage" class="alert alert-success text-center">
            Delivery saved successfully!
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <!-- Left Side: Form Inputs -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Type of Notice</label>
                            <select class="form-control" v-model="dataValues.notice_id">
                                <option value="1">Billing Notice</option>
                                <option value="2">Demand Notice</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select District</label>
                            <select class="form-control" @change="selectDistrict" v-model="dataValues.district_id">
                                <option v-for="district in districts" :value="district.id">{{ district.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Select Project</label>
                            <Select2 class=" select2 custom-select-style" v-model="dataValues.project_id" :options="projectList" @change="myChangeProject" />
                        </div>
                        <div class="form-group">
                            <label>Search Beneficiary</label>
                            <Select2 class="select2" v-model="dataValues.beneficiary_id" :options="beneficiaries" @change="updateAddress" />
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" v-model="dataValues.address" disabled />
                        </div>
                        <div class="form-group">
                            <label>ComCode</label>
                            <input type="text" class="form-control" v-model="dataValues.com_code" disabled />
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <h5>Capture Delivery Photo</h5>

                        <video v-if="!dataValues.capturedPhotoURL" ref="camera" autoplay class="camera-preview"></video>

                        <img v-if="dataValues.capturedPhotoURL" :src="dataValues.capturedPhotoURL" class="captured-photo" />

                        <canvas ref="canvas" class="d-none"></canvas>

                        <div class="mt-3">
                            <button v-if="!dataValues.capturedPhotoURL" class="btn btn-success btn-sm mx-1" @click="startCamera">Start Camera</button>
                            <button v-if="!dataValues.capturedPhotoURL" class="btn btn-primary btn-sm mx-1" @click="capturePhoto">Capture Photo</button>

                            <button v-if="dataValues.capturedPhotoURL" class="btn btn-warning btn-sm mx-1" @click="retakePhoto">Retake Photo</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
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
            showSuccessMessage: false,
            myValue:'',
            myOptions: ['op1', 'op2', 'op3'], // or [{id: key, text: value}, {id: key, text: value}]
            dataValues: {
                notice_id: '',
                district_id: '',
                project_id: '',
                beneficiary_id: '',
                address: '', 
                com_code:'',
                photo: null,
                capturedPhotoURL: null,
            },
            projectList: [],
            beneficiaries: [],
            cameraStream: null // Track the camera stream
        };
    },
    components: { Select2 },
    methods: {
        selectDistrict() {
            axios.get(`deliveries/gather_project/${this.dataValues.district_id}`)
                .then(response => {
                    this.projectList = response.data.data;
                });
        },
        myChangeProject() {
            axios.get(`deliveries/gather_beneficiaries/${this.dataValues.project_id}`)
                .then(response => {
                    this.beneficiaries = response.data.data;
                });
        },
        updateAddress() { 
            // console.log(this.dataValues.beneficiary_id);

            const selectedBeneficiary = this.beneficiaries.find(b => b.id == this.dataValues.beneficiary_id);
            if (selectedBeneficiary) {
                this.dataValues.address = selectedBeneficiary.address;
                this.dataValues.com_code = selectedBeneficiary.com_code;
            } else {
                console.warn("Selected beneficiary not found!");
                this.dataValues.address = '';
            }
        },
        async startCamera() {
            try {
                const constraints = {
                    video: {
                        facingMode: { exact: "environment" }, // Use back camera
                    },
                };
                const videoElement = this.$refs.camera;
                this.cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                videoElement.srcObject = this.cameraStream;
            } catch (error) {
                console.error("Error accessing the camera:", error);
                alert("Unable to access the camera. Please check your browser permissions.");
            }
        },
        async capturePhoto() {
            const canvas = this.$refs.canvas;
            const context = canvas.getContext('2d');
            const video = this.$refs.camera;

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            this.dataValues.capturedPhotoURL = canvas.toDataURL('image/png');

            const blob = await fetch(this.dataValues.capturedPhotoURL).then(res => res.blob());
            this.dataValues.photo = new File([blob], "delivery_photo.png", { type: "image/png" });

            this.stopCamera();
        },
        retakePhoto() {
            this.dataValues.capturedPhotoURL = null;
            this.dataValues.photo = null;

            this.startCamera();
        },
        stopCamera() {
            if (this.cameraStream) {
                this.cameraStream.getTracks().forEach(track => track.stop());
                this.cameraStream = null;
            }
        },
        storeData() {
            const formData = new FormData();
            formData.append('notice_id', this.dataValues.notice_id);
            formData.append('district_id', this.dataValues.district_id);
            formData.append('project_id', this.dataValues.project_id);
            formData.append('beneficiary_id', this.dataValues.beneficiary_id);

            if (this.dataValues.photo) {
                formData.append('photo', this.dataValues.photo, 'delivery_photo.png');
            }

            axios.post('/deliveries/store', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(response => {
                console.log(response);
                this.showSuccessMessage = true;

                setTimeout(() => {
                    this.showSuccessMessage = false;
                }, 3000);

                this.resetForm();
            })
            .catch(error => {
                console.log(error);
                alert('Error saving delivery.');
            });
        },
        resetForm() {
            this.dataValues = {
                notice_id: '',
                district_id: '',
                project_id: '',
                beneficiary_id: '',
                address: '',
                photo: null,
                capturedPhotoURL: null,
            };

            this.startCamera();
        }
    },
};
</script>

<style scoped>
.select2{
    width: 100%;
    margin-bottom: 10px;
    align-items: center;
    display: block;
    width: 100%;
    height: calc(2.25rem + 2px);
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    box-shadow: inset 0 0 0 transparent;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.camera-preview, .captured-photo {
    width: 100%;
    height: 340px;
    border: 2px solid #ddd;
    border-radius: 8px;
    object-fit: cover;
}
</style>
