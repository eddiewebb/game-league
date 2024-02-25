// This file is run by the browser each time your view template is loaded

/**
 * Define variables that reference elements included in /views/index.html:
 */

const factionPieEl = document.getElementById('factionPie');
const playerPieEl = document.getElementById('playerPie');
const combinedRadarEl = document.getElementById('combinedRadar');
const gamesTableEl = document.getElementById('gamesTable');

Chart.register(ChartDataLabels);
/**
 * Functions to handle appending new content to /views/index.html
 */
const factionColors = {
  "Marquise":"orange",
  "Eyrie":"blue",
  "Vagabond":"gray",
  "Woodland Alliance":"green",
} 
const factionNames = {
  "Marquise (Cat)":"Marquise",
  "Eyrie (Bird)":"Eyrie",
  "Vagabond (Racoon)":"Vagabond",
  "Woodland Alliance":"Woodland Alliance",
} 
const labels =  [
  'Eyrie',
  'Vagabond',
  'Woodland Alliance',
  'Marquise'
];
const players = ['Caleb','Eli','Eddie','Rubin']


// Appends the blocks API response to the UI
const updateChart = function (apiResponse) {
  const factionWins = labels.reduce((full,current)=>(full[current]=0,full),{})
  const playerWins = players.reduce((full,current)=>(full[current]=0,full),{})
  const playerFactionWins = players.reduce((full,current)=>(full[current]=labels.reduce((full,current)=>(full[current]=0,full),{}),full),{})
  console.log(playerFactionWins);
  apiResponse.map( async (row,index)=>{
    const faction = factionNames[row['winning_faction']];
    const player = row['winning_player'];
    factionWins[faction]++ 
    playerWins[player]++ 
    playerFactionWins[player][faction]++ 
  });
  console.log(playerFactionWins);
  const data = {
    labels: labels,
      datasets: [{ 
        data: labels.map((key) => factionWins[key]),
        backgroundColor: labels.map((key)=> factionColors[key]),
        hoverOffset: 4,
        datalabels: {
          labels: {
            name: {
              align: 'top',
              font: {size: '14rem', weight: 'bold'},
              color: 'white',
              formatter: function(value, ctx) {
                return ctx.active
                  ? 'name'
                  : ctx.chart.data.labels[ctx.dataIndex];
              }
            },
            value: {
              align: 'bottom',
              backgroundColor: 'white',
              borderColor: 'white',
              borderWidth: 2,
              borderRadius: 4,
              padding: 4
            }
          }
        }

      }]
    };
  new Chart(factionPieEl, {
      type: 'pie',
      options: {
        plugins: {
            legend: {
                display: false
            }
        }
      },
      data: data
    });
  const data2 = {
      labels: players,
        datasets: [{
          data: players.map((key) => playerWins[key]),
          hoverOffset: 4,
          datalabels: {
            labels: {
              name: {
                align: 'top',
                font: {size: '14em', weight: 'bold'},
                color: 'white',
                formatter: function(value, ctx) {
                  return ctx.active
                    ? 'name'
                    : ctx.chart.data.labels[ctx.dataIndex];
                }
              },
              value: {
                align: 'bottom',
                backgroundColor: 'white',
                borderColor: 'white',
                borderWidth: 2,
                borderRadius: 4,
                padding: 4
              }
            }
          }
        }]
      };
  new Chart(playerPieEl, {
      type: 'pie',
      data:  data2,
      options: {
        plugins: {
            legend: {
                display: false
            }
        }
      },
    });

  const data3 = {
    labels: labels,
      datasets: 
        players.map((player)=>{
          return new Object({
            label: player,
            data:  labels.map((key) => playerFactionWins[player][key]),
          })
        })       
    };

  new Chart( combinedRadarEl, {
    type: 'radar',
    data:  data3,
    options: {
      plugins: {
          legend: {
              display: true
          }
      },scales: {
        r: {
            min: -.5,
            max: 2,
            ticks: {
              stepSize: 1
            }
        }
      }
    }

  });
}






