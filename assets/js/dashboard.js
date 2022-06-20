document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById("myChart")) {
        var cpc = $(".dashboard").data("cpc");

        showChart(cpc);
    }

    var form = document.querySelector(".js-dashboard-form");
    form.querySelector("select").addEventListener("change", function () {
        const data = new FormData(this.form);
        const url = new URL(
            this.form.getAttribute("action") || window.location.href
        );
        const params = new URLSearchParams();
        data.forEach((value, key) => {
            params.append(key, value);
        });
        let link = url.pathname + "?" + params.toString();
        const parameter = new URLSearchParams(link.split("?")[1] || "");
        parameter.set("ajax", 1);
        link = link.split("?")[0] + "?" + parameter.toString();
        $.ajax({
            url: link,
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        }).then((response) => {
            console.log(response);
            if (document.querySelector(".dashboard")) {
                document.querySelector(".dashboard").innerHTML =
                    response.report;
                showChart(response.cpc);
            }
        });
    });
});
/* globals Chart:false, feather:false */

function showChart(cpc) {
    // Graphs
    var ctx = document.getElementById("myChart");
    const months = [
        "Janvier",
        "Fevrier",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Aout",
        "Septembre",
        "Octobre",
        "Novembre",
        "Decembre",
    ];
    let color = ["red", "blue", "green", "violet", "indigo", "orange"];
    let exploitationResult = [];
    let financialResult = [];
    let currentResult = [];
    let noCurrentResult = [];
    let resultBeforeImpot = [];
    let resultNet = [];
    for (const month of months) {
        exploitationResult.push(cpc[month] ? cpc[month].exploitationResult : 0);
        financialResult.push(cpc[month] ? cpc[month].financialResult : 0);
        currentResult.push(cpc[month] ? cpc[month].currentResult : 0);
        noCurrentResult.push(cpc[month] ? cpc[month].noCurrentResult : 0);
        resultBeforeImpot.push(cpc[month] ? cpc[month].resultBeforeImpot : 0);
        resultNet.push(cpc[month] ? cpc[month].resultNet : 0);
    }
    // eslint-disable-next-line no-unused-vars
    var myChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: months,
            datasets: [
                {
                    data: exploitationResult,
                    lineTension: 0,
                    label: "Resultat d'exploitation",
                    backgroundColor: "transparent",
                    borderColor: color[0],
                    borderWidth: 4,
                    pointBackgroundColor: color[0],
                },
                {
                    data: financialResult,
                    lineTension: 0,
                    label: "Resultat financiers",
                    backgroundColor: "transparent",
                    borderColor: color[1],
                    borderWidth: 4,
                    pointBackgroundColor: color[1],
                },
                {
                    data: currentResult,
                    lineTension: 0,
                    label: "Resultat courant",
                    backgroundColor: "transparent",
                    borderColor: color[2],
                    borderWidth: 4,
                    pointBackgroundColor: color[2],
                },
                {
                    data: noCurrentResult,
                    lineTension: 0,
                    label: "Resultat non courant",
                    backgroundColor: "transparent",
                    borderColor: color[3],
                    borderWidth: 4,
                    pointBackgroundColor: color[3],
                },
                {
                    data: resultBeforeImpot,
                    lineTension: 0,
                    label: "Resultat Avant impot",
                    backgroundColor: "transparent",
                    borderColor: color[4],
                    borderWidth: 4,
                    pointBackgroundColor: color[4],
                },
                {
                    data: resultNet,
                    lineTension: 0,
                    label: "Resultat Net",
                    backgroundColor: "transparent",
                    borderColor: color[5],
                    borderWidth: 4,
                    pointBackgroundColor: color[5],
                },
            ],
        },
        options: {
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true,
                        },
                    },
                ],
            },
            legend: {
                display: true,
            },
        },
    });
}

window.showChart = showChart;
