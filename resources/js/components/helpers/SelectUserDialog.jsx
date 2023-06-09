import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Row, Col} from 'react-bootstrap';
import axios from "axios";

export default class SelectUserDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            userFound: false,
            foundUsers: []
        };

        this.nameInput = null;

        this.handleClose = (hasSentValue) => {
            this.setState({ show: false });
            if (this.props.onClosed) {
                this.props.onClosed(hasSentValue);
            }
        };

        this.handleShow = () => {
            this.setState({ show: true });
            setTimeout(function(that) {
                if(that.nameInput)
                {
                    that.nameInput.focus();
                }
            }, 200, this)
        };

    }
    componentDidUpdate(prevProps) {
        if (this.props.show != prevProps.show) {
            if(this.props.show) {
                this.handleShow();
            } else {
                this.handleClose();
            }
        }
    }

    async select(userId) {
        if (this.props.onSelectUser) {
            this.props.onSelectUser(userId);
        }
        this.handleClose(true);
    }

    async submitSearch(event)
    {
        event.preventDefault();
        await this.search();
    }

    async search() {
        try{
            const response = await axios.post('/api/users/search', {
                name: this.state.name
            });
            this.setState({foundUsers: response.data});
        } catch(error) {
            console.log(error.response);
        }
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        return (
                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.title || "Benutzer auswählen"}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form onSubmit={this.submitSearch.bind(this)}>
                            <Form.Group>
                                <Form.Label>Benutzer:</Form.Label>
                                <InputGroup>
                                    <Form.Control ref={(input) => { this.nameInput = input; }}  name="name" type="text" placeholder="Spielername" autoComplete="off" autoFocus="on" onChange={this.onChange.bind(this)} />
                                    <InputGroup.Append>
                                        <Button onClick={this.search.bind(this)} variant="outline-success">suchen</Button>
                                    </InputGroup.Append>
                                </InputGroup>
                            </Form.Group>
                        </Form>
                        {(this.state.foundUsers.length > 0) ?
                            <div><p><strong>Suchergebnisse:</strong></p>
                                {this.state.foundUsers.map((user, i) => {
                                    return <Row key={user.Id}>
                                                <Col>
                                                    {user.Name}
                                                </Col>
                                                <Col>
                                                    <Button variant="primary" size="sm" onClick={this.select.bind(this, user.Id)}>
                                                        {this.props.buttonText || "auswählen"}
                                                    </Button>
                                                </Col>
                                            </Row>
                                })}
                            </div>
                        : null}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
        );
    }
}

var selectUserDialog = document.getElementsByTagName('react-select-user-dialog');

for (var index in selectUserDialog) {
    const component = selectUserDialog[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<SelectUserDialog {...props} />, component);
    }
}

