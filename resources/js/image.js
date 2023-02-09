
import Croppie from 'croppie';
import Dropzone from 'dropzone'

const Image = (self = false) => ({
    base: 'profile/photo',
    showCroppie: false,
    self: self,
    hasImage: false,
    originalSrc: "",
    croppieEl: false,
    croppie: {},
    dropZone: false,
    files: [],
    path: "",
    point_x1: 0,
    point_x2: 0,
    point_y1: 0,
    point_y2: 0,
    csrf: false,
    initCroppie(croppie) {
        this.csrf = document.querySelector('meta[name="csrf-token"]').content;
        this.croppieEl = croppie;
        this._dropzone();
        
    },
    _croppie() {
        this.croppie = new Croppie(this.croppieEl, {
            viewport: { width: 340, height: 340, type: "circle" }, //circle
            boundary: { width: 340, height: 340 }, //default boundary container
            showZoomer: true,
            enableResize: false,
            update: () => this.update()
        });
    },
    _destroyCroppie() {
        console.log(this.croppie);
        if(typeof this.croppie.data != 'undefined') {
            this.croppie.destroy();
            console.log("destroyed");
        }
    },
    _dropzone() {
        this.dropZone = new Dropzone("#dropzone", {
            'headers': {
                'X-CSRF-TOKEN': this.csrf
            },
            maxFiles: 1,
            url: `/${this.base}`,
            acceptedFiles: 'image/*',
            maxfilesexceeded: function (file) {
                this.removeAllFiles();
                this.addFile(file);
            }
        });
        this.dropZone.on("complete", (file) => {
            this.updatePreview(file);
        });
        this.dropZone.on("success", (file, response) => {
            this.path = response.file;
        });
    },
    remove() {
        this.dropZone.removeAllFiles();
    },
    updatePreview(file) {
        var reader;
        
        this._destroyCroppie();
        this._croppie();

        reader = new FileReader();

        reader.onload = (e) => {
            this.showCroppie = true;
            this.originalSrc = e.target.result;
            this.bindCroppie(e.target.result);
        };
        reader.readAsDataURL(file);
    },
    update() {
        let res = this.croppie.get();
        this.point_x1 = res.points[0];
        this.point_y1 = res.points[1];
        this.point_x2 = res.points[2];
        this.point_y2 = res.points[3];

        if(self && typeof self.updatePoints === "function") {
            self.updatePoints({
                point_x1: this.point_x1,
                point_x2: this.point_x2,
                point_y1: this.point_y1,
                point_y2: this.point_y2,
                path: this.path,
            })
        }
    },
    clearPreview() {
        this.showCroppie = false;
        this.dropZone.removeAllFiles();

        if(self) {
            self.updatePoints({
                point_x1: 0,
                point_x2: 0,
                point_y1: 0,
                point_y2: 0,
                path: "",
            })
        }
        this.point_x1 = 0;
        this.point_x2 = 0;
        this.point_y1 = 0;
        this.point_y2 = 0;
        this.path = "";

        fetch(`/${this.base}`, {
            method: 'DELETE',
            disablePreviews: true,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrf
            },
            body: JSON.stringify({
                file: this.path
            })
        })
            .then(r => r.json())
            .then(r => console.log(r))
    },
    bindCroppie(src) {
        setTimeout(() => {
            this.croppie.bind({
                url: src
            });
        }, 200);
    }
}
)
export default Image;