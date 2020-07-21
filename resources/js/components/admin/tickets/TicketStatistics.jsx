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
        )

        if(this.state.status) {
            if(this.state.status !== 'Success') {
                let message = <p>Der Zugriff auf die Daten wurde verweigert!</p>;

                if(this.state.status === 'Error') {
                    message = <p>Die Daten konnten nicht geladen werden!</p>;
                }

                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                        </div>

                        <div className="card-body">
                            <div className="text-center">
                                {message}
                            </div>
                        </div>
                    </div>
                );
            } else {
                let chart;
                let fromTo;

                if(this.state.data.type === 'doughnut') {
                    chart = <Doughnut plugins={[showAllTooltipsPlugin]} data={this.state.data.data} options={this.state.data.options} />;
                } else if(this.state.data.type === 'bar') {
                    chart = <Bar plugins={[showAllTooltipsPlugin]} data={this.state.data.data} options={this.state.data.options} />;
                } else {
                    chart = <Line plugins={[showAllTooltipsPlugin]} data={this.state.data.data} options={this.state.data.options} />;
                }

                if(this.state.data.from && this.state.data.to) {
                    fromTo = <span>{this.state.data.from} - {this.state.data.to}</span>;
                } else if(this.state.data.date) {
                    fromTo = <span>{this.state.data.date}</span>;
                }



                return (
                    <div className="card">
                        <div className="card-header">{this.props.title}
                            <div className="float-right">
                                {fromTo}
                            </div>
                        </div>

                        <div className="card-body"  style={{'height': '40vh'}}>
                            {chart}
                        </div>
                    </div>
                );
            }
        }
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

