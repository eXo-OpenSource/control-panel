import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import {Line, Doughnut, Bar} from 'react-chartjs-2';
import { Spinner } from 'react-bootstrap';


export default class TicketStatistics extends Component {

    constructor() {
        super();
        this.state = {
            data: null,
            rawData: null,
            options: {
                'maintainAspectRatio': false,
                'scales': {
                    'yAxes': [
                        {
                            'ticks': {
                                min: 0,
                                suggestedMax: 30,
                                stepSize: 5
                            }
                        }
                    ]
                }
            }
        };
    }

    async componentDidMount() {

        try {
            const response = await axios.get('/api/admin/tickets');

            this.setState({
                rawData: response.data
            });

            response.data.map((element, i) => {
                response.data[i]['chartData'] = {
                    'labels': [],
                    'datasets': [
                        {
                            'label': 'gelöste Tickets',
                            'borderColor': 'rgba(206, 136, 82, 1)',
                            'backgroundColor': 'rgba(206, 136, 82, 0.2)',
                            'pointBorderColor': 'rgba(206, 136, 82, 1)',
                            'pointBackgroundColor': 'rgba(206, 136, 82, 1)',
                            'pointHoverBackgroundColor': 'rgba(206, 136, 82, 1)',
                            'data': []
                        },
                        {
                            'label': 'hinzugezogene Tickets',
                            'borderColor': 'rgba(130, 216, 106, 1)',
                            'backgroundColor': 'rgba(130, 216, 106, 0.2)',
                            'pointBorderColor': 'rgba(130, 216, 106, 1)',
                            'pointBackgroundColor': 'rgba(130, 216, 106, 1)',
                            'pointHoverBackgroundColor': 'rgba(130, 216, 106, 1)',
                            'data': []
                        }
                    ]
               };

               element.data.map((entry, i2) => {
                   response.data[i]['chartData']['labels'].push('Woche ' + entry.Week);
                   response.data[i]['chartData']['datasets'][0]['data'].push(entry.ResolvedCount);
                   response.data[i]['chartData']['datasets'][1]['data'].push(entry.ConsultedCount);
               });
            });


            this.setState({
                data: response.data
            });
        } catch (error) {
        }
    }

    render() {
        if(!this.state.data)
            return <div className="text-center"><Spinner animation="border" /></div>;

        return (
            <div>
                <div className="row">
                    <div className="col-12">
                        <div className="card">
                            <div className="card-header">
                                Tickets
                            </div>
                            <div className="card-body">
                                <table className="table table-sm">
                                    <tr>
                                        <th>Name</th>
                                        <th>Rang</th>
                                        {this.state.rawData[0].data.map((element, i) => {
                                            return <th key={element.Week}>{element.Week}</th>;
                                        })}
                                    </tr>
                                    {this.state.rawData.map((element, i) => {
                                        return (
                                            <tr key={element.Id}>
                                                <td>{element.Name}</td>
                                                <td>{element.Rank}</td>
                                                {element.data.map((element2, i2) => {
                                                    return <td key={element2.Week}>{element2.ResolvedCount > 0 ? element2.ResolvedCount : '-'}</td>;
                                                })}
                                            </tr>
                                        );
                                    })}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="row">
                    {this.state.data.map((element, i) => {
                        return (
                            <div className="col-6" key={element.Id}>
                                <div className="card">
                                    <div className="card-header">
                                        {element.Name}
                                    </div>
                                    <div className="card-body" style={{'height': '40vh'}}>
                                        <Line data={element.chartData} options={this.state.options} />
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>
        );
    }
}

var charts = document.getElementsByTagName('react-ticket-statistics');
for (var index in charts) {
    const component = charts[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<TicketStatistics {...props} />, component);
    }
}

