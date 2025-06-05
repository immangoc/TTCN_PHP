// Thêm danh mục
const adressAll = document.querySelector("#underlay");
const adresscloseAll = document.querySelector("#under-close");

if (adressAll && adresscloseAll) {
  adressAll.addEventListener("click", function () {
    document.querySelector(".underlay").style.display = "flex";
  });
  adresscloseAll.addEventListener("click", function () {
    document.querySelector(".underlay").style.display = "none";
  });
} else {
  console.warn("Không tìm thấy #underlay hoặc #under-close trong DOM");
}

// Thêm loại sản phẩm
const adressbtn = document.querySelector("#underlay-js");
const adressclose = document.querySelector("#underlay-close");

if (adressbtn && adressclose) {
  adressbtn.addEventListener("click", function () {
    document.querySelector(".underlay-js").style.display = "flex";
  });
  adressclose.addEventListener("click", function () {
    document.querySelector(".underlay-js").style.display = "none";
  });
} else {
  console.warn("Không tìm thấy #underlay-js hoặc #underlay-close trong DOM");
}

// Thêm màu
const adressbtncolor = document.querySelector("#color-js");
const colorclose = document.querySelector("#color-close");

if (adressbtncolor && colorclose) {
  adressbtncolor.addEventListener("click", function () {
    document.querySelector(".color-js").style.display = "flex";
  });
  colorclose.addEventListener("click", function () {
    document.querySelector(".color-js").style.display = "none";
  });
} else {
  console.warn("Không tìm thấy #color-js hoặc #color-close trong DOM");
}
