<script>
var JQGridTest = JQGridTest || {};

JQGridTest.PersonGrid = (function () {
    var $PersonGrid;

    var init = function () {
        $PersonGrid = $("#PersonGrid").jqGrid(buildpersonGridInitObject())
    };

    var addPersonDetails = function () {
        var data = [];
        for (var i = 0; i < 1000; i++) {
            data[i] = {
                PersonNumber: "P " + i,
                BDate: "01/01/" + i,
                JobStatus: "Working",
                JobStatusValue : "WKNG",
                DEPT: "DEPT " + i,
                JobDescription: "Job " + i,
                MinExp: i,
                MaxExp: i,
                PersonDbKey: i,
                Person_Person_DbKey: i + i               
            };
        }

        $PersonGrid.addRowData("Person_Person_DbKey", data, "last");
    };

    var buildpersonGridInitObject = function () {
        return {
            datatype: "local",
            scroll: true,
            height: 500,
            autowidth:true,
            width: 945,
            shrinkToFit: false,
            multiselect: true,
            sortname: "PersonNumber",
            loadonce: false,
            sortable: true,
            altRows: true,
            altclass: "alternateRow",
            rowNum: 10000,
            gridview: true,
            colNames: [
                        "Person number",
                        "birth date",
                        "JOB Status Value",
                        "JOB status",
                        "DEPT",
                        "Job description",
                        "min exp",
                        "max exp",
                        "Person DbKey",
                        "Person_Person_Dbkey"
                      ],
            colModel: [
                        { name: "PersonNumber", index: "PersonNumber", sortable: true, width: 80, align: "center", sorttype: "text" },
                        { name: "BDate", index: "BDate", sortable: true, sorttype: "date", width: 80, align: "center", sorttype: "date" },
                        { name: "JobStatus", index: "JobStatus", sortable: true, hidden: true, sorttype: "text" },
                        { name: "JobStatusValue", index: "JobStatusValue", sortable: true, width: 80, align: "center", sorttype: "integer" },
                        { name: "DEPT", index: "DEPT", sortable: true, width: 100, align: "center", sorttype: "text" },
                        { name: "JobDescription", index: "JobDescription", sortable: true, width: 368, align: "left", sorttype: "text" },
                        { name: "MinExp", index: "MinExp", sortable: true, sorttype: "integer", width: 80, align: "center" },
                        { name: "MaxExp", index: "MaxExp", sortable: true, sorttype: "integer", width: 80, align: "center" },
                        { name: "PersonDbKey", index: "PersonDbKey", sortable: true, hidden: true, sorttype: "integer" },
                        { name: "Person_Person_DbKey", index: "Person_Person_DbKey", key: true, sortable: true, hidden: true, sorttype: "text" }
                      ]
        };
    };

    return {
        init: init,
        addPersonDetails: addPersonDetails
    };

})();

Calling jQuery Code:

$(document).ready(function () {
    JQGridTest.PersonGrid.init();
    $("#selectMinMax").click(function () {
        var d1 = new Date();
        JQGridTest.PersonGrid.addPersonDetails();
        var d2 = new Date();
        var d3 = (d2 – d1);
        var seconds = Math.round((d2 – d1)/1000)
        $("#loadTime").text(d3 + " , " + seconds);
        //alert(d3);
    });
});

</script>
<table id="PersonGrid"></table>
<input type="button" id="selectPerson" name="selectPerson" value="select" class="gridbutton" />