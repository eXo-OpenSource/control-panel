import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";

export default class Vehicle extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: null
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            if(this.state.data === null) {
                this.loadData();
            }
        };
    }

    async loadData() {
        const response = await axios.get('/api/vehicles/' + this.props.id);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    render() {
        let modalBody = <div className="text-center"><Spinner animation="border" /></div>;

        if(this.state.data) {
            let premium = this.state.data.Premium > 0 ? 'Ja' : 'Nein';
            let tunings = <></>;
            let map = <></>;

            if(this.state.data.PosX) {
                map = <img src={'https://exo-reallife.de/images/map.php?x=' + this.state.data.PosX + '&y=' + this.state.data.PosY + '&height=180&width=200'} />
            }

            if(this.state.data.Tunings) {
                let soundvan = this.state.data.Tunings && this.state.data.Tunings.Special === 1 ? <><dt>Spezial</dt><dd>Soundvan</dd></> : <></>;
                let neon = this.state.data.Tunings.Neon > 0 ? 'Ja' : 'Nein';

                tunings = <>
                            <h3>Tunings</h3>
                            <dl>
                                <dt>Neon</dt>
                                <dd>{neon}</dd>
                                <dt>Hupe</dt>
                                <dd>{this.state.data.Tunings.CustomHorn}</dd>
                                {soundvan}
                            </dl>
                          </>;
            }

            modalBody = <>
                            <div className="row">
                                <div className="col-md-6">
                                    <dl>
                                        <dt>Premium</dt>
                                        <dd>{premium}</dd>
                                        <dt>Kilometerstand</dt>
                                        <dd>{this.props.distance} km</dd>
                                        <dt>Lackfarbe</dt>
                                        <dd className="d-flex">
                                            <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col1}}></div>
                                            <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col2}}></div>
                                            <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col3}}></div>
                                            <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col4}}></div>
                                        </dd>
                                    </dl>
                                    {tunings}
                                </div>
                                <div className="col-md-6">
                                    <img className="mb-2" src={'https://exo-reallife.de/images/veh/Vehicle_' + this.props.model + '.jpg'} />
                                    {map}
                                </div>
                            </div>
                        </>
        }

        return (
            <>
                <div className="card">
                    {this.props.premium > 0 ? <span className="image-badge-left image-badge-success">Premium</span> : <></>}
                    {
                        this.props.unique ?
                            <span className="image-badge-right image-badge-danger">Einzelstück</span>
                            : (this.props.ultraRare ?
                                <span className="image-badge-right image-badge-warning">Sehr Selten</span>
                                : (this.props.rare ?
                                    <span className="image-badge-right image-badge-info">Selten</span>
                                    : <></>))
                    }
                    {this.props.premium > 0 && this.props.premiumOwner ? <span className="image-badge-left-bottom image-badge-success">{this.props.premiumOwner}</span> : <></>}

                    <img className="bd-placeholder-img card-img-top" src={'https://exo-reallife.de/images/veh/Vehicle_' + this.props.model + '.jpg'} />

                    <div className="card-body">
                        <div className="card-title d-flex justify-content-between align-items-start">
                            <h5>{this.props.name}</h5>

                            <button className="btn btn-transparent p-0" onClick={this.handleShow}>
                                <i className="fas fa-info-circle"></i>
                            </button>
                        </div>
                        <dl className="vehicle-info">
                            <dt>Kilometerstand</dt>
                            <dd>{this.props.distance} km</dd>
                            <dt>Lackfarbe</dt>
                            <dd className="d-flex">
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col1}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col2}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col3}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col4}}></div>
                            </dd>
                        </dl>
                    </div>
                </div>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Fahrzeug: {this.props.name} ({this.props.id})</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {modalBody}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var vehicles = document.getElementsByTagName('react-vehicle');

for (var index in vehicles) {
    const component = vehicles[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Vehicle {...props} />, component);
    }
}

