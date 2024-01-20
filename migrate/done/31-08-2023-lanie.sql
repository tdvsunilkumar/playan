ALTER TABLE `hr_changeof_schedules` CHANGE `id` `id` int NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_work_sched_in` time NULL AFTER `hrtc_time_out`;
ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_work_sched_out` time NULL AFTER `hrtc_work_sched_in`;
ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_late` int NULL AFTER `hrtc_work_sched_out`;
ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_undertime` int NULL AFTER `hrtc_work_sched_out`;
ALTER TABLE `hr_timecards` ADD COLUMN IF NOT EXISTS `hrtc_ot` int NULL AFTER `hrtc_work_sched_out`;