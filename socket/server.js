
var Redis = require('ioredis');
var redis = new Redis();
// 訂閱 redis 的 notification 頻道，也就是我們在事件中 broadcastOn 所設定的
redis.subscribe('notification', function(err, count) {
  console.log('connect!');
});
// 當該頻道接收到訊息時就列在 terminal 上
redis.on('message', function(channel, notification) {
  console.log(notification);
});
