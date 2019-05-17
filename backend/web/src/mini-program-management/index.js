import React, {useState} from "react";
import styled from "styled-components";
import ReactDOM from "react-dom";
import {Steps, Icon, Button, Alert, Modal, message, LocaleProvider, Input, Spin, Tag} from 'antd';
import zhCN from 'antd/lib/locale-provider/zh_CN';
import compareVersions from 'compare-versions'
import axios from 'axios'
import ClassNames from 'classnames'
import '../utils/ajax'

const Step = Steps.Step;
const antIcon = <Icon type="loading" style={{ fontSize: 24 }} spin />;

const {auditStatus, versions} = window;

// const auditStatus = {
//     auditid: 438893451,
//     errcode: 0,
//     errmsg: "ok",
//     status: 4,
// };
//
// const versions = {
//     version: "1.4.0",
//     commit_version: "",
//     audit_version: "",
//     release_version: "",
// };

const {version, commit_version, audit_version, release_version} = versions;

const versionsArray = [version, commit_version, audit_version, release_version];

// console.log('auditStatus', auditStatus);
// console.log('versions', versions);

const initVersionList = [
    {
        label: '版本库最新版本',
        value: version
    },
    {
        label: '体验版版本',
        value: commit_version
    },
    {
        label: '审核中版本',
        value: auditStatus.status === 2 ? commit_version : '无'
    },
    {
        label: '已审核版本',
        value: audit_version
    },
    {
        label: '线上版本',
        value: release_version
    },
];

const stepInfoList = [
    {
        title: '上传',
        buttonText: '上传小程序代码',
        icon: 'cloud-upload',
        url: 'commit',
        description: '将代码上传到微信公众平台，使其成为体验版'
    },
    {
        title: '审核',
        buttonText: '提交审核',
        icon: 'audit',
        url: 'submit-audit',
        description: '微信小程序均需要审核后才能上线，一般情况下24小时内即可完成审核'
    },
    {
        title: '审核结果未知',
        icon: 'question-circle',
        url: 'submit-audit',
        status: {
            0: {
                title: '审核通过',
                icon: 'check-circle',
            },
            1: {
                title: '审核不通过',
                icon: 'close-circle',
                stepStatus: 'error',
                buttonText: '重新提交审核',
                description: '审核不通过，原因：'
            },
            2: {
                title: '等待审核结果',
                icon: 'sync',
                description: '请耐心等待微信的审核结果，一般情况下 24 小时内即可完成审核'
            },
            3: {
                title: '审核已撤回',
                icon: 'rollback',
                stepStatus: 'error',
                buttonText: '重新提交审核',
                description: '有人手动操作撤回了本次审核，请重新提交审核'
            }
        }
    },
    {
        title: '发布',
        buttonText: '发布新版',
        icon: 'export',
        url: 'release',
        description: '新版本的小程序需要等下一次冷启动，即退出超过一定时间（目前是5分钟）后才会应用上。'
    },
];

const colorList = ['magenta', 'red', 'volcano', 'orange', 'gold', 'lime', 'green', 'cyan', 'blue', 'geekblue', 'purple'];

function getStepInfo(stepIndex, currentStepInfo, auditStatusCode) {
    if (stepIndex === 2 && currentStepInfo.status && currentStepInfo.status[auditStatusCode]) {
        return {
            title: currentStepInfo.status[auditStatusCode].title,
            icon: currentStepInfo.status[auditStatusCode].icon,
            description: currentStepInfo.status[auditStatusCode].description
        }
    } else {
        return {
            title: currentStepInfo.title,
            icon: currentStepInfo.icon,
            description: currentStepInfo.description
        }
    }
}

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //The maximum is exclusive and the minimum is inclusive
}

export default class MiniProgramManagement extends React.Component {
    constructor(props) {
        super(props);

        // 查找第一个需要完成的步骤
        let currentStep = versionsArray.findIndex((currentVersion, index) => {
            const nextItem = versionsArray[index + 1];
            return !nextItem || compareVersions(currentVersion, nextItem) > 0
        });
        console.log(currentStep)
        let auditStatusCode = auditStatus.status;

        if (currentStep === 1 && typeof auditStatusCode === 'number') {
            currentStep = auditStatusCode === 0 ? 3 : 2;
        } else if (currentStep >= 2) {
            currentStep++;
            auditStatusCode = 0;
        } else {
            auditStatusCode = 2;
        }

        if (currentStep === -1) {
            currentStep = 4;
        }

        // console.log('currentStep: ', currentStep);

        this.state = {
            currentStep,
            versionList: initVersionList,
            auditStatusCode,
            wechatId: '',
            qrCodeToTry: null,
            isLoadingAction: false,
            trierList: null,
            isShowModalForQrCode: false,
            isShowModalForTrier: false,
            isAddingTrier: false
        };
    }

    static defaultProps = {};

    static propTypes = {};


    commitAction = () => {
        const {currentStep} = this.state;
        let {versionList} = this.state;
        const {url, buttonText} = stepInfoList[currentStep];

        this.setState({isLoadingAction: true});
        axios.post(`/agent/${url}`).then(() => {
            message.success((buttonText || '重新提交审核') + '成功');
            if (currentStep === 2) {
                // 重新提交审核，不切换到下一个步骤
                this.setState({
                    auditStatusCode: 2,
                    isLoadingAction: false
                })
            } else {
                // 切换到下一个步骤
                versionList[currentStep + 1].value = versionList[currentStep].value;

                this.setState({
                    currentStep: currentStep + 1,
                    isLoadingAction: false,
                    versionList
                });

                if (currentStep === 3) {
                    Modal.info({
                        title: '提示',
                        content: stepInfoList[3].description,
                        cancelText: '',
                    })
                }
            }
        }).catch(() => {
            this.setState({isLoadingAction: false})
        })
    };

    showManagingTrier = () => {
        this.setState({ isShowModalForTrier: true, trierList: null });
        axios.get('/agent/trier-list').then(data => {
            this.setState({ trierList: data })
        }).catch((err) => this.setState({ trierList: err }));
    };

    addTrier = () => {
        const {wechatId} = this.state;

        if (wechatId) {
            this.setState({ isAddingTrier: true });
            axios.post('/agent/set-trier', {
                wechatId
            }).then(() => {
                message.success('设置体验者成功');
                this.setState({ isAddingTrier: false, wechatId: '' });
                this.hideModalForTrier();
            }).catch(() => {
                this.setState({ isAddingTrier: false })
            });
        } else {
            message.error('请输入用户微信号')
        }
    };

    changeWechatId = (e) => {
        this.setState({ wechatId: e.target.value })
    };

    showModalForQrCode = () => {
        this.setState({ isShowModalForQrCode: true, qrCodeToTry: '' });
        axios.get('/agent/qr-code-to-try').then(data => {
            this.setState({ qrCodeToTry: data })
        }).catch((err) => this.setState({ qrCodeToTry: `error: ${err}` }));
    };

    hideModalForQrCode = () => {
        this.setState({ isShowModalForQrCode: false });
    };

    hideModalForTrier = () => {
        this.setState({ isShowModalForTrier: false });
    };

    render() {
        const {currentStep, versionList, isLoadingAction, auditStatusCode, isShowModalForQrCode,
            isShowModalForTrier, qrCodeToTry, trierList, isAddingTrier, wechatId} = this.state;
        const currentStepInfo = stepInfoList[currentStep];
        const currentStatusInfo = currentStepInfo && currentStepInfo.status && currentStepInfo.status[auditStatusCode];
        const currentStepInfoWithStatus = currentStepInfo && getStepInfo(currentStep, currentStepInfo, auditStatusCode);

        return (
            <Root>
                <Container>
                    {currentStep <= 3 && (
                        <Steps
                            current={currentStep}
                            status={currentStatusInfo && currentStatusInfo.stepStatus}
                        >
                            {stepInfoList.map(({title, icon, status}, index) => {
                                if (index === 2 && status && status[auditStatusCode]) {
                                    return (
                                        <Step
                                            key={index}
                                            title={status[auditStatusCode].title}
                                            icon={<Icon type={status[auditStatusCode].icon}/>}
                                        />
                                    )
                                } else {
                                    return (
                                        <Step
                                            key={index}
                                            title={title}
                                            icon={<Icon type={icon}/>}
                                        />
                                    )
                                }
                            })}
                        </Steps>
                    )}
                    <Content>
                        <Description>
                            {currentStep === 0 && (
                                <MessageAlert>
                                    <Alert
                                        message={(
                                            <UpdateTip>
                                                有新的版本<LatestVersion>{version}</LatestVersion>，建议进行更新。
                                                <GotoChangeLog href="/weapp/log">看看更新了什么</GotoChangeLog>
                                            </UpdateTip>
                                        )}
                                        type="success"
                                        showIcon
                                        icon={<Icon type="bulb" theme="twoTone" />}
                                    />
                                </MessageAlert>
                            )}
                            {currentStepInfo ? (
                                <MainDescription
                                    className={ClassNames({
                                        'error': currentStep === 2 && (auditStatusCode === 1 || auditStatusCode === 3),
                                        'warning': currentStep === 2 && !currentStatusInfo
                                    })}
                                >
                                    <Icon
                                        type={currentStepInfoWithStatus.icon}
                                        style={{ fontSize: '40px'}}
                                    />
                                    <DescriptionText>
                                        {currentStepInfoWithStatus.description || currentStepInfoWithStatus.title}
                                        {(currentStep === 2 && auditStatusCode === 1) && <Reason>{auditStatus.errmsg}</Reason>}
                                    </DescriptionText>
                                </MainDescription>
                            ) : (
                                <LatestTip>
                                    <Icon type="safety-certificate" theme="filled" style={{ fontSize: '120px'}} />
                                    <TipText>您的小程序线上版本已经是最新的</TipText>
                                </LatestTip>
                            )}
                        </Description>
                        {(currentStepInfo && currentStepInfo.buttonText || currentStatusInfo && currentStatusInfo.buttonText) && (
                            <Operation>
                                <Button
                                    type='primary'
                                    icon={stepInfoList[currentStep].icon}
                                    loading={isLoadingAction}
                                    onClick={this.commitAction}
                                >
                                    {currentStepInfo.buttonText || currentStatusInfo && currentStatusInfo.buttonText}
                                </Button>
                            </Operation>
                        )}
                        <Versions>
                            {versionList.map(({label, value}, index) => (
                                <VersionItem key={index}>
                                    <Label>{label}</Label>
                                    <Value>{value || '无'}</Value>
                                </VersionItem>
                            ))}
                        </Versions>
                        <Handler>
                            <Button type='primary' onClick={this.showModalForQrCode}>查看体验版二维码</Button>
                            <Button type='primary' onClick={this.showManagingTrier}>添加体验者</Button>
                        </Handler>
                    </Content>
                </Container>
                <Modal
                    visible={isShowModalForQrCode}
                    title='查看体验二维码'
                    cancelButtonProps={{style: {display: 'none'}}}
                    onOk={this.hideModalForQrCode}
                    onCancel={this.hideModalForQrCode}
                >
                    {qrCodeToTry ? (qrCodeToTry.startsWith('error: ') ? <Reason>{qrCodeToTry.substr(6)}</Reason> :
                        <img src={qrCodeToTry} alt=""/>) : (
                        <LoadingBar><Spin indicator={antIcon} /><span>加载中...</span></LoadingBar>
                    )}
                </Modal>
                <Modal
                    visible={isShowModalForTrier}
                    title='设置体验者'
                    confirmLoading={isAddingTrier}
                    okText='添加体验者'
                    onOk={this.addTrier}
                    onCancel={this.hideModalForTrier}
                >
                    {trierList ? (typeof trierList === 'string' ? <Reason>{trierList}</Reason> : (
                        <TrierList>
                            {trierList.map((trier, index) => (
                                <Tag
                                    key={index}
                                    color={colorList[getRandomInt(0, colorList.length - 1)]}
                                >{trier.wechatid}</Tag>
                            ))}
                        </TrierList>
                    )) : (
                        <LoadingBar><Spin indicator={antIcon} /><span>加载体验者中...</span></LoadingBar>
                    )}
                    <Input placeholder='请输入用户微信号' value={wechatId} onChange={this.changeWechatId} />
                </Modal>
            </Root>
        )
    }
}

const Root = styled.div`
  
`;

const Container = styled.div`
  max-width: 600px;
  margin: auto;
`;

const Content = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  min-height: 300px;
  padding: 20px;
`;

const Description = styled.div`
  max-width: 400px;
  margin-bottom: 30px;
`;

const Reason = styled.div`
  color: #f5222d;
`;

const Operation = styled.div`
    margin-bottom: 20px;
`;

const Versions = styled.div`
  display: table-row-group;
`;

const VersionItem = styled.div`
  display: table-row;
`;

const Label = styled.div`
  display: table-cell;
  padding: 5px 20px;
`;

const Value = styled.div`
  display: table-cell;
  padding: 5px 10px;
  text-align: center;
  color: #40a9ff;
`;

const Handler = styled.div`
  display: flex;
  margin-top: 20px;
  > button {
    margin: 0 10px;
  }
`;

const LatestTip = styled.div`
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-around;
  color: #33a968;
`;

const TipText = styled.div`
  margin-top: 30px;
  font-size: 1.5em;
`;

const MainDescription = styled.div`
  display: flex;
  align-items: center;
  color: #40a9ff;
  &.error {
    color: #f5222d;
  }
  &.warning {
    color: #f49c00;
  }
`;

const DescriptionText = styled.div`
  margin-left: 20px;
  font-size: 1.2em;
`;

const MessageAlert = styled.div`
  margin-bottom: 20px;
`;

const TrierList = styled.div`
  margin-bottom: 20px;
`;

const LoadingBar = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 10px 0;
  > span {
    margin-left: 20px;
  }
`;

const UpdateTip = styled.div`
  
`

const LatestVersion = styled.span`
  margin: 0 5px;
  color: #f49c00;
`;

const GotoChangeLog = styled.a`
  margin-left: 5px;
`;

ReactDOM.render(
    <LocaleProvider locale={zhCN}>
        <MiniProgramManagement />
    </LocaleProvider>,
    document.getElementById('mini-program-management')
);