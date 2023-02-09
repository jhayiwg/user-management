import Image from '../image'
import Dropzone from 'dropzone'

const UserIndex = () => ({
    points: {
        point_x1: 0,
        point_x2: 0,
        point_y1: 0,
        point_y2: 0,
        path: "",
    },
    modal: {
        show: false,
        action_label: "",
        image: false,
        user: {
            id: '',
            name: '',
            email: '',
            photo: '',
        },
        error: {},
    },
    init() {
        this.reset();
        this.image = Image;
    },
    reset() {
        this.modal.user = {
            id: '',
            name: '',
            email: '',
            photo: '',
        };
        this.modal.error = {
            errors: [],
            message: '',
        };
    },
    async edit(id) {
        this.reset();

        this.modal.show = true;
        let data = await window.axios.get(`/users/${id}`).then((res) => res.data);

        this.modal.action_label = `Edit ${data.name}`
        this.modal.user = { ...this.user, ...data };

        let holder = document.getElementById('photo-wrapper');
        holder.innerHTML = "";
        let img = document.createElement('img');
        img.src = data.photo;
        img.className = 'rounded-full border-gray-100 shadow-sm w-24 h-24';
        holder.appendChild(img);

        this._initImage();

    },
    _initImage() {
        this.image = Image(this);
        this.image.base = 'users/photo';
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(dz => dz.destroy())
        }
        let holder = document.getElementById('croppie-holder');
        holder.innerHTML = "";
        let img = document.createElement('img');
        img.id = "img-croppie";
        holder.appendChild(img);
        this.image.initCroppie(document.getElementById('img-croppie'));
    },
    create() {
        this.reset();
        this.modal.user = {
            id: '',
            name: '',
            email: '',
            photo: '',
        };
        this.modal.show = true;

        this._initImage();
        document.getElementById('photo-wrapper').innerHTML = "";
        this.modal.action_label = `Create`
    },

    updatePoints(params) {
        this.points = { ...params };
    },
    async destroy(id) {
        if (confirm("Delete!") != true) {
            return;
        }

        await window.axios.delete(`/users/${id}`)
            .then((res) => {
                window.LaravelDataTables["users-table"].ajax.reload(null, false);
            })
            .catch((error) => {
                if (error.response) {
                    this.modal.error = { ...error.response.data };
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
            });

    },
    async store() {
        let data = {
            name: this.modal.user.name,
            email: this.modal.user.email
        }
        this.points
        if (
            typeof this.points != 'undefined' &&
            typeof this.points.point_x1 != 'undefined') {
            data.points = {
                x1: this.points.point_x1,
                x2: this.points.point_x2,
                y1: this.points.point_y1,
                y2: this.points.point_y2,
            };
            data.file = this.points.path
        }
        let response = await window.axios.post(`/users`, data)
            .then((res) => {
                window.LaravelDataTables["users-table"].ajax.reload(null, false);
                this.modal.show = false;
            })
            .catch((error) => {
                if (error.response) {
                    this.modal.error = { ...error.response.data };
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
                console.log(error.config);
            });

    },
    async update(e) {
        let data = {
            name: this.modal.user.name,
            email: this.modal.user.email,
            _method: 'patch'
        }

        this.points
        if (
            typeof this.points != 'undefined' &&
            typeof this.points.point_x1 != 'undefined') {
            data.points = {
                x1: this.points.point_x1,
                x2: this.points.point_x2,
                y1: this.points.point_y1,
                y2: this.points.point_y2,
            };
            data.file = this.points.path
        }
        let response = await window.axios.post(`/users/${this.modal.user.id}`, data)
            .then((res) => {
                window.LaravelDataTables["users-table"].ajax.reload(null, false);
                this.modal.show = false;
                alert('saved');
            })
            .catch((error) => {
                if (error.response) {
                    this.modal.error = { ...error.response.data };
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
                console.log(error.config);
            });

    }
});

export default UserIndex;