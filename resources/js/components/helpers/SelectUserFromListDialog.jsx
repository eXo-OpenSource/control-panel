import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Row, Col} from 'react-bootstrap';
import axios from "axios";
import SelectUserDialog from "./SelectUserDialog";

export default class SelectUserFromListDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: [],
            selectedUsers: [],
            showAddUserDialog: false
        };

        this.handleClose = (hasSentValue) => {
            this.setState({ show: false, showAddUserDialog: false });
            if (this.props.onClosed) {
                this.props.onClosed(hasSentValue);
            }
        };

        this.handleShow = () => {
            this.setState({
                show: true,
                data: null,
                selectedUsers: []
            });
            this.loadData();
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

    async selectMultiple() {
        if (this.props.onSelectUser) {
            this.props.onSelectUser(this.state.selectedUsers);
        }
        this.handleClose(true);
    }

    async loadData() {
        try{
            const response = await axios.post('/api/users/list', {
                type: this.props.type,
                id: this.props.id
            });

            this.setState({
                data: response.data,
                selectedUsers: []
            });
            console.log(response.data);
        } catch(error) {
            console.log(error.response);
        }
    }

    async toggleSelected(id) {
        let selected = this.state.selectedUsers;

        if(selected.includes(id)) {
            const index = selected.indexOf(id);
            if (index !== -1) selected.splice(index, 1);
        } else {
            selected.push(id);
        }

        this.setState({
            selectedUsers: selected
        });
    }

    async openSearchDialog() {
        this.setState({
            show: false,
            showAddUserDialog: true,
        });
    }

    async hideUserDialog(hasSentData) {
        if(hasSentData) {
            this.setState({
                show: true,
                showAddUserDialog: false,
            });
        }
    }

    render() {
        return (<div>
                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.title || "Benutzer auswählen"}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {(this.state.data && this.state.data.length > 0) ?
                            <div>
                                {this.state.data.map((user, i) => {
                                    return <Row key={user.Id}>
                                        {this.props.multiple ? <Col xs={1}>
                                            <input type="checkbox" onChange={this.toggleSelected.bind(this, user.Id)} />
                                        </Col> : '' }
                                        <Col>
                                            {user.Name}
                                        </Col>
                                        <Col>
                                            {user.Rank}
                                        </Col>
                                        {this.props.multiple ? '' : <Col>
                                            <Button variant="primary" size="sm" onClick={this.select.bind(this, user.Id)}>
                                                {this.props.buttonText || "auswählen"}
                                            </Button>
                                        </Col>}
                                    </Row>
                                })}
                            </div>
                        : null}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.openSearchDialog.bind(this)}>
                            Suche
                        </Button>
                        {this.props.multiple ? <Button variant="primary" onClick={this.selectMultiple.bind(this)}>
                            Hinzufügen
                        </Button> : ''}
                        <Button variant="secondary" onClick={this.handleClose.bind(this)}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
                <SelectUserDialog show={this.state.showAddUserDialog} buttonText="hinzufügen" onClosed={this.hideUserDialog.bind(this)} onSelectUser={this.select.bind(this)} />
        </div>);
    }
}

var selectUserDialog = document.getElementsByTagName('react-select-user-from-list-dialog');

for (var index in selectUserDialog) {
    const component = selectUserDialog[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<SelectUserFromListDialog {...props} />, component);
    }
}

