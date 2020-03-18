import React, { Component } from "react";
import { Link } from "react-router-dom";
import {
  Card,
  CardHeader,
  CardBody,
  CardFooter,
  Button,
  Label,
  Row,
  Col
} from "reactstrap";
import { Modal, Table, Input, Icon } from "antd";
import ImageZoom from "react-medium-image-zoom";
import reqwest from "reqwest";
import Highlighter from "react-highlight-words";
import Moment from "moment";

import {
  path_accessorCompetence,
  baseUrl,
  path_users,
  formatDate
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
import Axios from "axios";

const Search = Input.Search;

class ListSkill extends Component {
  constructor(props) {
    super(props);
    this.state = {
      open: false,
      data: [],
      accessor_competence_id: "",
      user_id: "",
      competence_field_code: "",
      sub_schema_name: "",
      certificate_file: "",
      list_skill: false,
      payload: [],
      status: ""
    };
  }

  fetch = (params = {}) => {
    const { user_id } = this.props.match.params;
    this.setState({
      loading: true
    });
    const auth = Digest(
      path_users + "/" + user_id + path_accessorCompetence,
      "GET"
    );
    reqwest({
      url: baseUrl + path_users + "/" + user_id + path_accessorCompetence,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = {
          ...this.state.pagination
        };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        }
      });
  };

  componentDidMount() {
    this.fetch();
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }
    this.fetch({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting
      // ...filters
    });
  };

  handleSearch = searchText => {
    const { user_id } = this.props.match.params;

    this.setState({ loading: true });
    const auth = Digest(
      path_users + "/" + user_id + path_accessorCompetence,
      "GET"
    );
    reqwest({
      url:
        baseUrl +
        path_users +
        "/" +
        user_id +
        path_accessorCompetence +
        "?search=" +
        searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.fetch();
    }
  };

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleClick = event => {
    event.preventDefault();

    const expired_date = this.state.expired_date;
    const formatDate = Moment(expired_date).format("YYYY-MM-DD h:mm:ss");

    const accessor_competence_id = this.state.accessor_competence_id;
    const authentication = Digest(
      path_accessorCompetence + "/" + accessor_competence_id,
      "PUT"
    );
    var data = {};

    if (typeof formatDate !== "undefined") data["expired_date"] = formatDate;

    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date
      },
      url: baseUrl + path_accessorCompetence + "/" + accessor_competence_id,
      data: data
    };
    Axios(options)
      .then(response => {
        if (response.data.responseStatus === "SUCCESS") {
          this.setState({ list_skill: true, open: false });
          this.fetch();
        }
      })
      .catch(error => {
        let responseJSON = error.response;
        if (responseJSON.data.responseStatus === "ERROR") {
        }
      });
  };

  onOpenModal = accessor_competence_id => {
    this.setState({ open: true, accessor_competence_id });

    const authentication = Digest(
      path_accessorCompetence + "/" + accessor_competence_id,
      "GET"
    );
    const options = {
      method: authentication.method,
      headers: {
        Authorization: authentication.digest,
        "X-Lsp-Date": authentication.date
      },
      url: baseUrl + path_accessorCompetence + "/" + accessor_competence_id
    };
    Axios(options).then(response => {
      this.setState({
        payload: response.data.data
      });
    });
  };

  onCloseModal = () => {
    this.setState({ open: false });
  };

  render() {
    const { data, pagination, loading, open } = this.state;

    const columns = [
      {
        key: "sub_schema_number",
        dataIndex: "sub_schema_number",
        title: multiLanguage.name + " " + multiLanguage.competence,
        sorter: true,
        width: "50%",
        render: (value, row) => {
          return (
            <div>
              ({value})-{row.sub_schema_name}
            </div>
          );
        }
      },
      {
        key: "certificate_file",
        dataIndex: "certificate_file",
        title: multiLanguage.file,
        sorter: false,
        render: value => {
          return (
            <ImageZoom
              image={{
                src: baseUrl + value,
                alt: "",
                className: "img",
                style: { width: "5em" }
              }}
              zoomImage={{
                src: baseUrl + value,
                alt: ""
              }}
            />
          );
        }
      },
      {
        key: "accessor_competence_id",
        dataIndex: "accessor_competence_id",
        title: "Status",
        sorter: false,
        render: (value, row) => {
          return (
            <div>
              {row.verification_flag === 0 ? (
                <Button
                  className="outline"
                  onClick={this.onOpenModal.bind(this, value)}
                  color="info"
                >
                  {multiLanguage.confirm}
                </Button>
              ) : (
                <Button className="outline" color="light" disabled>
                  {`${multiLanguage.activation} ${
                    multiLanguage.until
                  } ${formatDate(row.expired_date)}`}
                </Button>
              )}
            </div>
          );
        }
      }
    ];

    return (
      <div className="animated fadeIn">
        <Modal
          visible={open}
          onCancel={this.onCloseModal}
          onOk={this.handleClick}
          center
        >
          <h2>Form {multiLanguage.activation}</h2>
          <Label>
            {multiLanguage.alertexpired}.
            <p />
            {multiLanguage.noteListSkill}
          </Label>
          <p />
          <b>{multiLanguage.expiredDate}:</b>{" "}
          <input
            type="date"
            id="expired_date"
            onChange={this.handleChange}
            name="expired_date"
            required
          />
        </Modal>

        <Card>
          <CardHeader
            style={{ textAlign: "center" }}
          >{`${multiLanguage.list} ${multiLanguage.skill} Assessor`}</CardHeader>
          <CardBody>
            <Row style={{ marginBottom: "12px" }}>
              <Col>
                {`${multiLanguage.searching} `}
                <Search
                  placeholder={multiLanguage.search}
                  onSearch={this.handleSearch}
                  onChange={this.handleChange}
                  style={{ width: 301 }}
                />{" "}
              </Col>
            </Row>
            <Row>
              <Col>
                <Table
                  columns={columns}
                  rowKey="uid"
                  dataSource={data}
                  pagination={pagination}
                  loading={loading}
                  onChange={this.handleTableChange}
                  striped
                />
              </Col>
            </Row>
          </CardBody>
          <CardFooter>
            <Link to={`/Assessors`}>
              <Button className="outline" color="danger">
                <i className="fa fa-chevron-left" />
                {` ${multiLanguage.back}`}
              </Button>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default ListSkill;
