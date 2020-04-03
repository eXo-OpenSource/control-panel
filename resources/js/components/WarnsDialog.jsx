import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Table} from 'react-bootstrap';
import axios from "axios";

export default class WarnsDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            showWarn: false,
            data: null,
            duration: '',
            reason: '',
            message: '',
            messageType: ''
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleWarnClose = () => {
            this.setState({ showWarn: false });
            this.setState({ show: true });
        };

        this.handleWarnShow = () => {
            this.setState({ show: false });
            this.setState({ showWarn: true });
        };

        this.handleMessageClose = () => {
            this.setState({ showMessage: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            this.loadData();
        };
    }

    async loadData() {
        const response = await axios.get('/api/admin/users/' + this.props.id + '/warns');

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async warn() {
        try {
            const response = await axios.post('/api/admin/users/' + this.props.id + '/warns', {
                type: 'ban',
                duration: this.state.duration,
                reason: this.state.reason
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            }

            this.setState({ showWarn: false });
            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ showWarn: false });
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    async deleteWarn(id) {
        try {
            const response = await axios.delete('/api/admin/users/' + this.props.id + '/warns/' + id);

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            }

            this.setState({ showWarn: false });
            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ showWarn: false });
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        let body = <div className="text-center"><Spinner animation="border" /></div>;

        if(this.state.data) {
            body =
                <Table>
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Grund</th>
                        <th>Admin</th>
                        <th>Datum</th>
                        <th>Läuft ab</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.data.map((warn, i) => {
                        return (
                            <tr key={warn.Id}>
                                <td>{warn.Id}</td>
                                <td>{warn.Reason}</td>
                                <td>{warn.Admin}</td>
                                <td>{warn.Created}</td>
                                <td>{warn.Expires}</td>
                                <td>
                                    <Button size="sm" variant="danger" onClick={() => this.deleteWarn(warn.Id)}>
                                        Löschen
                                    </Button>
                                </td>
                            </tr>
                        )
                    })}
                    </tbody>
                </Table>;
        }

        return (
            <>
                <Button variant="danger" onClick={this.handleShow}>
                    Warns
                </Button>

                <Modal show={this.state.show} onHide={this.handleClose} size="lg">
                    <Modal.Header closeButton>
                        <Modal.Title>Verwarnungen: {this.props.name}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {body}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="danger" onClick={this.handleWarnShow}>
                            Verwarnen
                        </Button>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>

                <Modal show={this.state.showWarn} onHide={this.handleWarnClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.name} verwarnen</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group>
                                <Form.Label>Dauer</Form.Label>
                                <InputGroup>
                                    <Form.Control name="duration" type="text" placeholder="Dauer" onChange={this.onChange.bind(this)} />
                                    <InputGroup.Append>
                                        <InputGroup.Text>Tagen</InputGroup.Text>
                                    </InputGroup.Append>
                                </InputGroup>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Grund</Form.Label>
                                <InputGroup>
                                    <Form.Control name="reason" type="text" placeholder="Grund" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="danger" onClick={this.warn.bind(this)}>
                            Verwarnen
                        </Button>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>

                <Modal show={this.state.showMessage} onHide={this.handleMessageClose} dialogClassName={'modal-' + this.state.messageType}>
                    <Modal.Header closeButton>
                        <Modal.Title>Status</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <span>{this.state.message}</span>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleMessageClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var warns = document.getElementsByTagName('react-warns-dialog');

for (var index in warns) {
    const component = warns[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<WarnsDialog {...props} />, component);
    }
}
