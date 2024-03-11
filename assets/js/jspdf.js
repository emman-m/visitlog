$(function(){
    $('#exportPDF').on('click', function() {
        const element = document.getElementById("prntPdf");

        html2canvas(element, {
            backgroundColor:null,
            height:element.clientHeight+100
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/jpg');
            const pdf = new jsPDF();
            const imgWidth = 210;
            const pageHeight = 297;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            let heightLeft = imgHeight;
            let position = 0;

            pdf.addImage(imgData, 'JPG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'JPG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }
            pdf.save($('.header-title').html()+".pdf");
        });
    })
})